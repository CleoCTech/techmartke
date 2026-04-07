<?php

namespace App\Services;

use App\Models\Product;

class TradeInValuationService
{
    // Condition multipliers
    const CONDITION_WEIGHTS = [
        'flawless' => 1.0,
        'good' => 0.85,
        'fair' => 0.50,
        'broken' => 0.15,
    ];

    // Storage capacity value additions (KES)
    const STORAGE_BONUS = [
        '64GB' => 0,
        '128GB' => 1000,
        '256GB' => 3000,
        '512GB' => 5000,
        '1TB' => 8000,
    ];

    // Battery replacement cost (KES)
    const BATTERY_PENALTY = 3000;
    const BATTERY_THRESHOLD = 85;

    // Platform margin (profit)
    const MARGIN = 0.20;

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
        // Base value: use product's base_price as the "current market value" for like-new
        $baseValue = $product ? (float) $product->base_price : 0;

        if ($baseValue <= 0) {
            return ['estimated_value' => 0, 'breakdown' => ['error' => 'Cannot value this device']];
        }

        $breakdown = [];

        // Step 1: Base market value (like-new resale = ~60% of retail for ex-uk, ~50% for new)
        $resaleRate = ($product->condition === 'new') ? 0.50 : 0.60;
        $marketValue = $baseValue * $resaleRate;
        $breakdown['base_market_value'] = round($marketValue);

        // Step 2: Apply condition multiplier
        $conditionMultiplier = self::CONDITION_WEIGHTS[$condition] ?? 0.50;
        $afterCondition = $marketValue * $conditionMultiplier;
        $breakdown['condition'] = $condition;
        $breakdown['condition_multiplier'] = $conditionMultiplier;
        $breakdown['after_condition'] = round($afterCondition);

        // Step 3: Storage bonus
        $storageBonus = self::STORAGE_BONUS[$storage] ?? 0;
        $afterStorage = $afterCondition + $storageBonus;
        if ($storageBonus > 0) {
            $breakdown['storage_bonus'] = $storageBonus;
        }

        // Step 4: Battery health penalty
        $batteryPenalty = 0;
        if ($batteryHealth !== null && $batteryHealth < self::BATTERY_THRESHOLD) {
            $batteryPenalty = self::BATTERY_PENALTY;
            $afterStorage -= $batteryPenalty;
            $breakdown['battery_penalty'] = -$batteryPenalty;
            $breakdown['battery_health'] = $batteryHealth;
        }

        // Step 5: Damage deductions
        $damagePenalty = 0;
        if ($hasCracks) {
            $crackPenalty = min($afterStorage * 0.15, 5000);
            $damagePenalty += $crackPenalty;
            $breakdown['crack_penalty'] = -round($crackPenalty);
        }
        if ($hasRepairs) {
            $repairPenalty = min($afterStorage * 0.10, 3000);
            $damagePenalty += $repairPenalty;
            $breakdown['repair_penalty'] = -round($repairPenalty);
        }
        if (!empty($problemsDescription)) {
            $problemPenalty = min($afterStorage * 0.05, 2000);
            $damagePenalty += $problemPenalty;
            $breakdown['problems_penalty'] = -round($problemPenalty);
        }

        $afterDamage = $afterStorage - $damagePenalty;

        // Step 6: Apply margin
        $finalOffer = $afterDamage * (1 - self::MARGIN);
        $finalOffer = max(0, round($finalOffer / 500) * 500); // Round to nearest 500

        $breakdown['platform_fee'] = '-' . (self::MARGIN * 100) . '%';
        $breakdown['final_offer'] = $finalOffer;

        return [
            'estimated_value' => $finalOffer,
            'breakdown' => $breakdown,
        ];
    }
}
