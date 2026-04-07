<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Trade-In Request</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

        <!-- Header -->
        <div style="background: linear-gradient(135deg, #059669, #047857); color: white; padding: 24px; text-align: center;">
            <h1 style="margin: 0; font-size: 22px;">🔁 New Trade-In Request</h1>
            <p style="margin: 4px 0 0; opacity: 0.85; font-size: 13px;">TechMart KE</p>
        </div>

        <!-- Body -->
        <div style="padding: 24px;">
            <div style="background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 12px; padding: 16px; margin-bottom: 20px; text-align: center;">
                <p style="margin: 0; font-size: 12px; color: #166534; text-transform: uppercase; font-weight: 600;">Estimated Value</p>
                <p style="margin: 4px 0 0; font-size: 28px; font-weight: 800; color: #15803d;">KSh {{ number_format($tradeIn->estimated_value ?? 0) }}</p>
            </div>

            <h2 style="margin: 0 0 12px; font-size: 16px; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Device Details</h2>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Device</td>
                    <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #111;">
                        {{ $tradeIn->product?->name ?? $tradeIn->custom_device_name ?? 'Unknown' }}
                        @if(!$tradeIn->product_id)
                            <span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 999px; font-size: 10px; margin-left: 4px;">Custom</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Storage</td>
                    <td style="padding: 8px 0; text-align: right; color: #111;">{{ $tradeIn->storage_capacity ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Condition</td>
                    <td style="padding: 8px 0; text-align: right; color: #111; text-transform: capitalize;">{{ $tradeIn->condition }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Battery Health</td>
                    <td style="padding: 8px 0; text-align: right; color: #111;">{{ $tradeIn->battery_health }}%</td>
                </tr>
                @if($tradeIn->has_cracks)
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Cracks</td>
                    <td style="padding: 8px 0; text-align: right; color: #dc2626; font-weight: 600;">⚠️ Yes</td>
                </tr>
                @endif
                @if($tradeIn->has_repairs)
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Previous Repairs</td>
                    <td style="padding: 8px 0; text-align: right; color: #d97706; font-weight: 600;">🔧 Yes</td>
                </tr>
                @endif
                @if($tradeIn->problems_description)
                <tr>
                    <td colspan="2" style="padding: 12px 0;">
                        <p style="margin: 0 0 4px; color: #666; font-size: 12px; text-transform: uppercase;">Problems</p>
                        <p style="margin: 0; padding: 10px; background: #fef2f2; border-left: 3px solid #fca5a5; color: #7f1d1d; font-size: 13px;">{{ $tradeIn->problems_description }}</p>
                    </td>
                </tr>
                @endif
            </table>

            <h2 style="margin: 24px 0 12px; font-size: 16px; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Customer Contact</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Name</td>
                    <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #111;">{{ $tradeIn->customer_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666; font-size: 14px;">Phone (WhatsApp)</td>
                    <td style="padding: 8px 0; text-align: right; color: #111;">
                        <a href="tel:{{ $tradeIn->customer_phone }}" style="color: #059669; font-weight: 600; text-decoration: none;">{{ $tradeIn->customer_phone }}</a>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 24px; padding: 16px; background: #f9fafb; border-radius: 8px; text-align: center;">
                <p style="margin: 0 0 8px; font-size: 13px; color: #666;">Manage this request in the admin dashboard:</p>
                <a href="{{ config('app.url') }}/admin/trade-in/{{ $tradeIn->uuid }}" style="display: inline-block; padding: 10px 20px; background: #059669; color: white; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 600;">View Request</a>
            </div>
        </div>

        <div style="text-align: center; padding: 16px; background: #f9fafb; color: #999; font-size: 11px;">
            TechMart KE — Smart Shopping, Better Decisions
        </div>
    </div>
</body>
</html>
