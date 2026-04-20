@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-file-signature mr-2"></i>{{ $quotation->quotation_no }}</h1></div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    @if($quotation->status !== 'converted')
                        <form action="{{ route('quotations.convert', $quotation) }}" method="POST" class="d-inline" onsubmit="return confirm('Convert this to invoice?')">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-exchange-alt mr-1"></i> Convert to Invoice</button>
                        </form>
                    @endif
                    <a href="{{ route('quotations.index') }}" class="btn btn-default btn-sm"><i class="fas fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong class="text-muted d-block text-sm">Date</strong>{{ $quotation->date->format('d M Y') }}</div>
                    <div class="col-md-3"><strong class="text-muted d-block text-sm">Valid Until</strong>{{ $quotation->valid_until?->format('d M Y') ?? '—' }}</div>
                    <div class="col-md-3"><strong class="text-muted d-block text-sm">Customer</strong>{{ $quotation->customer?->name ?? '—' }}</div>
                    <div class="col-md-3 text-right">
                        <strong class="text-muted d-block text-sm">Status</strong>
                        @php
                            $badge = match($quotation->status) {
                                'draft' => 'secondary', 'sent' => 'info', 'accepted' => 'success',
                                'rejected' => 'danger', 'expired' => 'dark', 'converted' => 'primary',
                            };
                        @endphp
                        <span class="badge badge-{{ $badge }} px-3 py-2" style="font-size: 14px;">{{ ucfirst($quotation->status) }}</span>
                    </div>
                </div>
                @if($quotation->subject)
                <hr><strong>Subject:</strong> {{ $quotation->subject }}
                @endif

                @if($quotation->status === 'converted' && $quotation->convertedInvoice)
                <div class="alert alert-success mt-3">
                    <i class="fas fa-check-circle mr-2"></i> Converted to invoice
                    <a href="{{ route('invoices.show', $quotation->convertedInvoice) }}" class="ml-2">{{ $quotation->convertedInvoice->invoice_no }}</a>
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-list mr-1"></i> Items</h3></div>
            <div class="card-body p-0">
                <table class="table table-bordered m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Description</th>
                            <th>Warranty</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotation->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->warranty ?? '—' }}</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                            <td class="text-right">&#2547; {{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right font-weight-bold">&#2547; {{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @if($quotation->terms)
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Terms & Conditions</h3></div>
                    <div class="card-body"><pre style="white-space: pre-wrap; margin: 0;">{{ $quotation->terms }}</pre></div>
                </div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table m-0">
                            <tr><td>Subtotal</td><td class="text-right">&#2547; {{ number_format($quotation->subtotal, 2) }}</td></tr>
                            @if($quotation->tax > 0)<tr><td>Tax</td><td class="text-right">&#2547; {{ number_format($quotation->tax, 2) }}</td></tr>@endif
                            @if($quotation->discount > 0)<tr><td>Discount</td><td class="text-right text-danger">- &#2547; {{ number_format($quotation->discount, 2) }}</td></tr>@endif
                            <tr class="thead-dark"><th class="text-white">Total</th><th class="text-right text-white">&#2547; {{ number_format($quotation->total, 2) }}</th></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
