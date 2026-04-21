<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation {{ $quotation->quotation_no }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f7fb; margin: 0; padding: 20px; color: #334155; }
        .container { max-width: 640px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0ea5e9, #6366f1); padding: 30px; color: #fff; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 4px 0 0; opacity: 0.9; }
        .body { padding: 30px; }
        .greeting { font-size: 16px; margin-bottom: 20px; }
        .custom-message { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; margin: 20px 0; border-radius: 6px; font-style: italic; }
        .meta { background: #f8fafc; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; }
        .meta p { margin: 6px 0; }
        .meta strong { color: #1e293b; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th { background: #f1f5f9; padding: 10px; text-align: left; font-size: 13px; color: #475569; }
        table td { padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        table td.right { text-align: right; }
        .total-row { background: linear-gradient(135deg, #0ea5e9, #6366f1); color: #fff; }
        .total-row td { font-weight: 700; font-size: 16px; border-bottom: none; }
        .footer { background: #f8fafc; padding: 20px 30px; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .attachment-note { background: #ecfdf5; color: #065f46; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Quotation #{{ $quotation->quotation_no }}</h1>
            <p>{{ config('app.name') }}</p>
        </div>

        <div class="body">
            <p class="greeting">Dear {{ $quotation->customer?->name ?? 'Customer' }},</p>

            <p>Thank you for your interest. Please find our quotation details below. A PDF copy is attached for your records.</p>

            @if($customMessage)
            <div class="custom-message">{{ $customMessage }}</div>
            @endif

            <div class="attachment-note">
                📎 PDF attached: <strong>{{ $quotation->quotation_no }}.pdf</strong>
            </div>

            <div class="meta">
                <p><strong>Quotation #:</strong> {{ $quotation->quotation_no }}</p>
                <p><strong>Date:</strong> {{ $quotation->date->format('d M Y') }}</p>
                @if($quotation->valid_until)
                <p><strong>Valid Until:</strong> {{ $quotation->valid_until->format('d M Y') }}</p>
                @endif
                @if($quotation->subject)
                <p><strong>Subject:</strong> {{ $quotation->subject }}</p>
                @endif
                <p><strong>Status:</strong> <span style="text-transform: uppercase;">{{ $quotation->status }}</span></p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="right">Qty</th>
                        <th class="right">Unit Price</th>
                        <th class="right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="right">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                        <td class="right">৳ {{ number_format($item->unit_price, 2) }}</td>
                        <td class="right">৳ {{ number_format($item->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="right">Subtotal:</td>
                        <td class="right">৳ {{ number_format($quotation->subtotal, 2) }}</td>
                    </tr>
                    @if($quotation->tax > 0)
                    <tr>
                        <td colspan="3" class="right">Tax:</td>
                        <td class="right">৳ {{ number_format($quotation->tax, 2) }}</td>
                    </tr>
                    @endif
                    @if($quotation->discount > 0)
                    <tr>
                        <td colspan="3" class="right">Discount:</td>
                        <td class="right">– ৳ {{ number_format($quotation->discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="3" class="right">TOTAL:</td>
                        <td class="right">৳ {{ number_format($quotation->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            @if($quotation->terms)
            <p><strong>Terms &amp; Conditions:</strong></p>
            <p style="white-space: pre-wrap;">{{ $quotation->terms }}</p>
            @endif

            @if($quotation->notes)
            <p><strong>Notes:</strong> {{ $quotation->notes }}</p>
            @endif

            <p style="margin-top: 30px;">We look forward to your confirmation.</p>
            <p>Regards,<br><strong>{{ config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            This is an automated email. Please do not reply directly to this message.
        </div>
    </div>
</body>
</html>
