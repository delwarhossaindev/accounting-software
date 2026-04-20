<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f7fb; margin: 0; padding: 20px; color: #334155; }
        .container { max-width: 640px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #6366f1, #8b5cf6); padding: 30px; color: #fff; }
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
        .total-row { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; }
        .total-row td { font-weight: 700; font-size: 16px; border-bottom: none; }
        .footer { background: #f8fafc; padding: 20px 30px; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $invoice->type === 'sales' ? 'Invoice' : 'Bill' }} #{{ $invoice->invoice_no }}</h1>
            <p>{{ config('app.name') }}</p>
        </div>

        <div class="body">
            <p class="greeting">Dear {{ $invoice->customer?->name ?? $invoice->supplier?->name ?? 'Customer' }},</p>

            <p>Please find the details of your {{ $invoice->type === 'sales' ? 'invoice' : 'bill' }} below.</p>

            @if($customMessage)
            <div class="custom-message">{{ $customMessage }}</div>
            @endif

            <div class="meta">
                <p><strong>Invoice #:</strong> {{ $invoice->invoice_no }}</p>
                <p><strong>Date:</strong> {{ $invoice->date->format('d M Y') }}</p>
                @if($invoice->due_date)
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
                @endif
                <p><strong>Status:</strong> <span style="text-transform: uppercase;">{{ $invoice->status }}</span></p>
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
                    @foreach($invoice->items as $item)
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
                        <td class="right">৳ {{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    @if($invoice->tax > 0)
                    <tr>
                        <td colspan="3" class="right">Tax:</td>
                        <td class="right">৳ {{ number_format($invoice->tax, 2) }}</td>
                    </tr>
                    @endif
                    @if($invoice->discount > 0)
                    <tr>
                        <td colspan="3" class="right">Discount:</td>
                        <td class="right">– ৳ {{ number_format($invoice->discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="3" class="right">TOTAL:</td>
                        <td class="right">৳ {{ number_format($invoice->total, 2) }}</td>
                    </tr>
                    @if($invoice->paid > 0)
                    <tr>
                        <td colspan="3" class="right">Paid:</td>
                        <td class="right" style="color: #10b981;">৳ {{ number_format($invoice->paid, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="right"><strong>Balance Due:</strong></td>
                        <td class="right" style="color: #ef4444;"><strong>৳ {{ number_format($invoice->due, 2) }}</strong></td>
                    </tr>
                    @endif
                </tfoot>
            </table>

            @if($invoice->notes)
            <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
            @endif

            <p style="margin-top: 30px;">Thank you for your business!</p>
            <p>Regards,<br><strong>{{ config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            This is an automated email. Please do not reply directly to this message.
        </div>
    </div>
</body>
</html>
