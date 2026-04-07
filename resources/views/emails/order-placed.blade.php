<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

        <!-- Header -->
        <div style="background: #000; color: white; padding: 24px; text-align: center;">
            <h1 style="margin: 0; font-size: 22px;">🛒 New Order Received</h1>
            <p style="margin: 4px 0 0; opacity: 0.7; font-size: 13px;">TechMart KE</p>
        </div>

        <!-- Body -->
        <div style="padding: 24px;">
            <h2 style="margin: 0 0 16px; font-size: 18px; color: #111;">Order #{{ $order->id }}</h2>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Customer</td>
                    <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #111;">{{ $order->customer_name ?? 'Guest' }}</td>
                </tr>
                @if($order->email)
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Email</td>
                    <td style="padding: 8px 0; text-align: right; color: #111;">{{ $order->email }}</td>
                </tr>
                @endif
                @if($order->phone ?? null)
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Phone</td>
                    <td style="padding: 8px 0; text-align: right; color: #111;">{{ $order->phone }}</td>
                </tr>
                @endif
                <tr style="border-top: 2px solid #f0f0f0;">
                    <td style="padding: 12px 0 8px; color: #666; font-size: 14px;">Total</td>
                    <td style="padding: 12px 0 8px; text-align: right; font-weight: 700; font-size: 20px; color: #000;">KSh {{ number_format($order->total_amount ?? 0) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Status</td>
                    <td style="padding: 8px 0; text-align: right;">
                        <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600;">{{ ucfirst($order->status ?? 'pending') }}</span>
                    </td>
                </tr>
            </table>

            @if($order->items && $order->items->count() > 0)
            <h3 style="margin: 24px 0 12px; font-size: 14px; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Items</h3>
            <table style="width: 100%; border-collapse: collapse;">
                @foreach($order->items as $item)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 10px 0; font-size: 14px; color: #111;">
                        {{ $item->product_name ?? $item->name }}
                        <span style="color: #999; font-size: 12px;">× {{ $item->quantity }}</span>
                    </td>
                    <td style="padding: 10px 0; text-align: right; font-weight: 600; color: #111;">KSh {{ number_format($item->price * $item->quantity) }}</td>
                </tr>
                @endforeach
            </table>
            @endif

            <div style="margin-top: 24px; padding: 16px; background: #f9fafb; border-radius: 8px; text-align: center;">
                <p style="margin: 0; font-size: 13px; color: #666;">
                    Login to admin dashboard to manage this order:<br>
                    <a href="{{ config('app.url') }}/admin/orders" style="color: #000; font-weight: 600;">{{ config('app.url') }}/admin/orders</a>
                </p>
            </div>
        </div>

        <div style="text-align: center; padding: 16px; background: #f9fafb; color: #999; font-size: 11px;">
            TechMart KE — Smart Shopping, Better Decisions
        </div>
    </div>
</body>
</html>
