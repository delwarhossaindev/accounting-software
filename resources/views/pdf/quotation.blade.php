@php
    function quotationNumberToWords($number) {
        $number = (float) $number;
        $digits_length = strlen((int)$number);
        $i = 0;
        $str = [];
        $words = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        ];
        $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];
        $number = (int) $number;
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number_part = $number % $divider;
            $number = (int)($number / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number_part) {
                $plural = (($counter = count($str)) && $number_part > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number_part < 21) ? $words[$number_part] . " " . $digits[$counter] . $plural . " " . $hundred
                        : $words[(int)($number_part / 10) * 10] . " " . $words[$number_part % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        return trim(preg_replace('/\s+/', ' ', implode('', array_reverse($str))));
    }
    $symbol = $company->currency_symbol ?? '৳';
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { box-sizing: border-box; }
    body { font-family: 'solaimanlipi', sans-serif; color: #000; font-size: 11px; margin: 0; padding: 0; }
    .header { text-align: center; padding-bottom: 10px; border-bottom: 2px solid #000; }
    .header .logo { height: 60px; vertical-align: middle; margin-right: 10px; }
    .header .company-name { font-size: 30px; font-weight: 900; letter-spacing: 2px; display: inline-block; vertical-align: middle; }
    .header .company-address { font-size: 12px; margin-top: 5px; font-weight: 600; }
    .header .company-contact { font-size: 11px; margin-top: 2px; font-weight: 600; }

    .quotation-title { background: #e9ecef; border: 1px solid #333; font-weight: 700; padding: 5px 15px; font-size: 13px; text-align: center; display: inline-block; margin: 10px auto; }

    .details-table { width: 100%; margin-top: 10px; border-collapse: collapse; }
    .details-table td { font-size: 11px; padding: 4px 6px; border: 1px solid #000; }
    .details-table .label { font-weight: 700; width: 15%; }
    .details-table .value { width: 35%; }

    .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .items-table th, .items-table td { border: 1px solid #000; padding: 5px; font-size: 10px; vertical-align: top; }
    .items-table th { background: #f0f0f0; font-weight: 700; text-align: center; }
    .items-table td.center { text-align: center; }
    .items-table td.right { text-align: right; }
    .items-table .product-name { font-weight: 700; }

    .totals-row td { border: 1px solid #000; padding: 5px; font-size: 11px; font-weight: 700; }
    .words-row { font-weight: 600; font-size: 10px; }

    .terms-section { margin-top: 15px; border: 1px solid #666; padding: 8px; font-size: 10px; }
    .terms-section .title { font-weight: 700; margin-bottom: 5px; }

    .signature-section { margin-top: 60px; width: 100%; }
    .signature-section td { font-size: 10px; padding-top: 30px; }
    .signature-line { border-top: 1px dotted #000; padding-top: 3px; font-weight: 700; }

    .footer-note { text-align: center; font-weight: 700; font-size: 12px; margin-top: 40px; padding: 6px; }

    .branches-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .branches-table td { text-align: center; font-size: 9.5px; padding: 4px; vertical-align: top; }
    .branches-table .branch-name { font-weight: 700; font-size: 11px; padding-bottom: 3px; }
</style>
</head>
<body>

<div class="header">
    @if($company->logo_path && file_exists(public_path('storage/' . $company->logo_path)))
        <img src="{{ public_path('storage/' . $company->logo_path) }}" class="logo" alt="Logo">
    @endif
    <span class="company-name">{{ strtoupper($company->name) }}</span>
    @if($company->address)
        <div class="company-address">📍 {{ $company->address }}</div>
    @endif
    @if($company->phone || $company->email)
        <div class="company-contact">
            @if($company->phone) 📞 {{ $company->phone }} @endif
            @if($company->email) &nbsp;|&nbsp; ✉ {{ $company->email }} @endif
        </div>
    @endif
</div>

<div style="text-align: center; margin: 10px 0;">
    <span class="quotation-title">QUOTATION</span>
</div>

<table class="details-table">
    <tr>
        <td class="label">Quotation No</td><td class="value">: <strong>{{ $quotation->quotation_no }}</strong></td>
        <td class="label">Date</td><td class="value">: {{ $quotation->date->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td class="label">Customer Name</td>
        <td class="value">: <strong>{{ $quotation->customer->name ?? '-' }}</strong></td>
        <td class="label">Valid Until</td>
        <td class="value">: {{ $quotation->valid_until?->format('d-m-Y') ?? '—' }}</td>
    </tr>
    <tr>
        <td class="label">Address</td>
        <td class="value">: {{ $quotation->customer->address ?? '' }}</td>
        <td class="label">Branch</td>
        <td class="value">: {{ $quotation->branch?->name ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Mobile</td>
        <td class="value">: {{ $quotation->customer->phone ?? '' }}</td>
        <td class="label">Status</td>
        <td class="value">: {{ ucfirst($quotation->status) }}</td>
    </tr>
    <tr>
        <td class="label">E-mail</td>
        <td class="value">: {{ $quotation->customer->email ?? '' }}</td>
        <td class="label">Print Time</td>
        <td class="value">: {{ now()->format('d-m-Y h:i:s a') }}</td>
    </tr>
    @if($quotation->subject)
    <tr>
        <td class="label">Subject</td>
        <td colspan="3" class="value">: <strong>{{ $quotation->subject }}</strong></td>
    </tr>
    @endif
</table>

<table class="items-table">
    <thead>
        <tr>
            <th width="4%">SI.</th>
            <th width="50%">Product Description</th>
            <th width="12%">Warranty</th>
            <th width="12%">Unit Price</th>
            <th width="8%">Quantity</th>
            <th width="14%">Total Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($quotation->items as $idx => $item)
        <tr>
            <td class="center">{{ $idx + 1 }}</td>
            <td><div class="product-name">{{ $item->description }}</div></td>
            <td class="center">{{ $item->warranty ?? '' }}</td>
            <td class="right">{{ number_format($item->unit_price, 2) }}</td>
            <td class="center">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
            <td class="right">{{ number_format($item->amount, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="totals-row">
            <td colspan="3" rowspan="{{ ($quotation->tax > 0 ? 1 : 0) + ($quotation->discount > 0 ? 1 : 0) + 2 }}" class="words-row">
                IN WORDS : {{ strtoupper(quotationNumberToWords($quotation->total)) }} TAKA ONLY.
            </td>
            <td colspan="2" class="right">Subtotal :</td>
            <td class="right">{{ number_format($quotation->subtotal, 2) }}</td>
        </tr>
        @if($quotation->tax > 0)
        <tr class="totals-row">
            <td colspan="2" class="right">Tax :</td>
            <td class="right">{{ number_format($quotation->tax, 2) }}</td>
        </tr>
        @endif
        @if($quotation->discount > 0)
        <tr class="totals-row">
            <td colspan="2" class="right">Discount :</td>
            <td class="right">- {{ number_format($quotation->discount, 2) }}</td>
        </tr>
        @endif
        <tr class="totals-row" style="background: #f0f0f0;">
            <td colspan="2" class="right">Grand Total :</td>
            <td class="right">{{ number_format($quotation->total, 2) }}</td>
        </tr>
    </tfoot>
</table>

@if($quotation->terms)
<div class="terms-section">
    <div class="title">Terms &amp; Conditions</div>
    <div style="white-space: pre-wrap;">{{ $quotation->terms }}</div>
</div>
@endif

@if($quotation->notes)
<div class="terms-section" style="margin-top: 8px;">
    <div class="title">Notes</div>
    <div style="white-space: pre-wrap;">{{ $quotation->notes }}</div>
</div>
@endif

<table class="signature-section">
    <tr>
        <td width="45%">
            <div class="signature-line">......................................................</div>
            <strong>Customer Acceptance</strong>
        </td>
        <td width="10%"></td>
        <td width="45%" style="text-align: right;">
            <div class="signature-line" style="margin-left: auto; display: inline-block; width: 250px;">......................................................</div>
            <br><strong>Authorized Signature</strong>
        </td>
    </tr>
</table>

@if($company->invoice_footer)
    <div class="footer-note">{{ $company->invoice_footer }}</div>
@endif

@if($branches->count() > 0)
<table class="branches-table">
    <tr>
        @foreach($branches as $branch)
        <td width="{{ floor(100 / $branches->count()) }}%">
            <div class="branch-name">{{ $branch->is_head_office ? 'Head Office' : $branch->name }}</div>
            @if($branch->address)<div>{{ $branch->address }}</div>@endif
        </td>
        @endforeach
    </tr>
</table>
@endif

</body>
</html>
