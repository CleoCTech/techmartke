<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedNotification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('Customer/Cart');
    }

    /**
     * GET /checkout — show the checkout form.
     * Cart contents live in browser localStorage, so the page loads them
     * client-side. We just render the empty form.
     */
    public function showCheckout()
    {
        return Inertia::render('Customer/Checkout');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:mpesa,card,cash_on_delivery',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.variant_details' => 'nullable|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $totalAmount += $item['unit_price'] * $item['quantity'];
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'variant_details' => $item['variant_details'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['unit_price'] * $item['quantity'],
                ]);

                if (!empty($item['variant_id'])) {
                    ProductVariant::where('id', $item['variant_id'])
                        ->decrement('stock_quantity', $item['quantity']);
                }
            }

            DB::commit();

            // Send admin notification (after commit so all data is persisted)
            try {
                $adminEmail = config('app.admin_notification_email', env('ADMIN_NOTIFICATION_EMAIL', 'dsldev00@gmail.com'));
                Mail::to($adminEmail)->send(new OrderPlacedNotification($order->load('items')));
            } catch (\Throwable $e) {
                Log::error('Failed to send order notification: ' . $e->getMessage());
            }

            return Inertia::render('Customer/Checkout', [
                'order' => $order->load('items'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process order: ' . $th->getMessage());
        }
    }
}
