@extends('layouts.app')

@section('content')
@php
    $label = $note->type === 'credit' ? 'Credit Note' : 'Debit Note';
    $partyLabel = $note->type === 'credit' ? 'Customer' : 'Supplier';
    $party = $note->type === 'credit' ? $note->customer : $note->supplier;
@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-file mr-2"></i>{{ $note->note_no }}</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('credit-debit-notes.index', ['type' => $note->type]) }}" class="btn btn-default btn-sm float-sm-right">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong class="text-muted d-block text-sm">Type</strong>
                        <span class="badge badge-{{ $note->type === 'credit' ? 'success' : 'warning' }}">{{ $label }}</span>
                    </div>
                    <div class="col-md-3"><strong class="text-muted d-block text-sm">Date</strong>{{ $note->date->format('d M Y') }}</div>
                    <div class="col-md-3"><strong class="text-muted d-block text-sm">{{ $partyLabel }}</strong>{{ $party?->name ?? '—' }}</div>
                    <div class="col-md-3"><strong class="text-muted d-block text-sm">Against Invoice</strong>
                        @if($note->invoice)
                            <a href="{{ route('invoices.show', $note->invoice) }}">{{ $note->invoice->invoice_no }}</a>
                        @else — @endif
                    </div>
                </div>
                @if($note->reason)<hr><strong>Reason:</strong> {{ $note->reason }}@endif
                @if($note->notes)<br><strong>Notes:</strong> {{ $note->notes }}@endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-list mr-1"></i> Items</h3></div>
            <div class="card-body p-0">
                <table class="table table-bordered m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Description</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($note->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                            <td class="text-right">&#2547; {{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right font-weight-bold">&#2547; {{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><th colspan="3" class="text-right">Subtotal</th><th class="text-right">&#2547; {{ number_format($note->subtotal, 2) }}</th></tr>
                        @if($note->tax > 0)<tr><th colspan="3" class="text-right">Tax</th><th class="text-right">&#2547; {{ number_format($note->tax, 2) }}</th></tr>@endif
                        <tr class="thead-dark"><th colspan="3" class="text-right text-white">Total</th><th class="text-right text-white">&#2547; {{ number_format($note->total, 2) }}</th></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
