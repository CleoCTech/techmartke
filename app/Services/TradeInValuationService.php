<?php

namespace App\Services;

use App\Models\Product;

/**
 * Trade-In Valuation Service
 *
 * Business rule (from owner):
 *   "Look at the cost price from our database, then deviate by KES 10,000
 *    if the battery is good and the phone is in good condition. If the
 *    battery is below 80% subtract more, plus damage deductions."
 *
 * Example: iPhone 14 Pro Max cost_price = KSh 70,000
 *   → max trade-in value (good battery + good condition) = KSh 60,000
 *   → because we want to make ~10,000 on the resale
 *
 * The formula is intentionally simple and margin-driven, not a stack of
 * compounding multipliers.
 */
class TradeInValuationService
{
    /**
     * Target gross margin TechMart wants to make on a trade-in resale.
     * This is the gap between what we pay the customer and our cost price.
     */
    const TARGET_MARGIN = 10000;

    /**
     * Battery threshold below which we apply a penalty.
     * Owner spec: "if the battery is below 80% subtract".
     */
    const BATTERY_THRESHOLD = 80;
    const BATTERY_PENALTY = 5000;

    /**
     * Condition adjustments applied AFTER (cost_price - target_margin).
     * 'good' is the baseline (no extra deduction).
     * 'flawless' gives the customer a small bonus.
     * 'fair' / 'broken' apply progressively larger deductions.
     */
    const CONDITION_ADJUSTMENTS = [
        'flawless' => 2000,    // +2,000 bonus for like-new
        'good'     => 0,       // baseline
        'fair'     => -7000,   // visible wear, still works
        'broken'   => -20000,  // major issues / not fully working
    ];

    /**
     * Damage deductions
     */
    const CRACK_PENALTY = 4000;
    const REPAIR_PENALTY = 3000;
    const PROBLEMS_PENALTY = 2000;

    /**
     * Storage tier bonuses (only meaningful as a small differentiator).
     */
    const STORAGE_BONUS = [
        '64GB'  => 0,
        '128GB' => 0,
        '256GB' => 1500,
        '512GB' => 3000,
        '1TB'   => 5000,
    ];

    /**
     * Minimum offer floor — we never offer below this even on broken devices.
     */
    const MIN_OFFER = 1000;

    /**
     * Calculate trade-in value for a product.
     */
    public static function calculate(
        ?Product $product,
        string $condition,
        ?int $batteryHealth,
        ?string $storage,
        bool $hasCracks,
        bool $hasRepairs,
        ?string $problemsDescription
    ): array {
        if (!$product) {
            return [
                'estimated_value' => 0,
                'breakdown' => ['error' => 'Select a device to get a valuation'],
            ];
        }

        // Use cost_price as the anchor. Fall back to base_price if no cost_price.
        $costPrice = (float) ($product->cost_price ?? 0);
        $usingFallback = false;

        if ($costPrice <= 0) {
            // No cost_price recorded — estimate cost as 80% of base_price as a fallback
            $costPrice = (float) $product->base_price * 0.8;
            $usingFallback = true;
        }

        if ($costPrice <= 0) {
            return [
                'estimated_value' => 0,
                'breakdown' => ['error' => 'Cannot value this device — cost price not configured'],
            ];
        }

        $breakdown = [];
        $breakdown['cost_price'] = round($costPrice);
        if ($usingFallback) {
            $breakdown['cost_price_note'] = 'Estimated from base_price (cost_price not set)';
        }

        // Step 1: start with cost_price - TARGET_MARGIN
        // This is the maximum we'd pay for a flawless device with perfect battery
        $base = $costPrice - self::TARGET_MARGIN;
        $breakdown['target_margin'] = -self::TARGET_MARGIN;
        $breakdown['after_margin'] = round($base);

        // Step 2: condition adjustment
        $conditionAdj = self::CONDITION_ADJUSTMENTS[$condition] ?? 0;
        $base += $conditionAdj;
        $breakdown['condition'] = $condition;
        if ($conditionAdj !== 0) {
            $breakdown['condition_adjustment'] = $conditionAdj;
        }

        // Step 3: battery penalty if below threshold
        if ($batteryHealth !== null && $batteryHealth < self::BATTERY_THRESHOLD) {
            // Scale the penalty: the worse the battery, the bigger the hit
            // 79% -> -5,000 (base), 60% -> -7,500, 40% -> -10,000
            $deficit = self::BATTERY_THRESHOLD - $batteryHealth;
            $batteryPenalty = self::BATTERY_PENALTY + ($deficit * 100);
            $base -= $batteryPenalty;
            $breakdown['battery_health'] = $batteryHealth . '%';
            $breakdown['battery_penalty'] = -round($batteryPenalty);
        } elseif ($batteryHealth !== null) {
            $breakdown['battery_health'] = $batteryHealth . '% (good)';
        }

        // Step 4: storage bonus (small)
        if ($storage && isset(self::STORAGE_BONUS[$storage])) {
            $bonus = self::STORAGE_BONUS[$storage];
            if ($bonus > 0) {
                $base += $bonus;
                $breakdown['storage_bonus'] = $bonus;
            }
        }

        // Step 5: damage deductions
        if ($hasCracks) {
            $base -= self::CRACK_PENALTY;
            $breakdown['crack_penalty'] = -self::CRACK_PENALTY;
        }
        if ($hasRepairs) {
            $base -= self::REPAIR_PENALTY;
            $breakdown['repair_penalty'] = -self::REPAIR_PENALTY;
        }
        if (!empty($problemsDescription)) {
            $base -= self::PROBLEMS_PENALTY;
            $breakdown['problems_penalty'] = -self::PROBLEMS_PENALTY;
        }

        // Step 6: floor at MIN_OFFER, round to nearest 500 for clean numbers
        $finalOffer = max(self::MIN_OFFER, $base);
        $finalOffer = round($finalOffer / 500) * 500;

        $breakdown['final_offer'] = $finalOffer;

        return [
            'estimated_value' => $finalOffer,
            'breakdown' => $breakdown,
        ];
    }
}
