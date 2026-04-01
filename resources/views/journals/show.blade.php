@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-book mr-2"></i>{{ $journal->voucher_no }}</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a href="{{ route('pdf.journal', $journal) }}" class="btn btn-secondary btn-sm" target="_blank">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </a>
                    <a href="{{ route('journals.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Header Info --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Voucher Details</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong class="text-muted d-block text-sm">Date</strong>
                        {{ $journal->date->format('d M Y') }}
                    </div>
                    <div class="col-md-3">
                        <strong class="text-muted d-block text-sm">Voucher Type</strong>
                        @php
                            $typeBadge = match($journal->voucher_type) {
                                'journal' => 'primary', 'receipt' => 'success', 'payment' => 'danger',
                                'contra' => 'warning', 'sales' => 'info', 'purchase' => 'secondary',
                                default => 'light',
                            };
                        @endphp
                        <span class="badge badge-{{ $typeBadge }} text-capitalize px-2 py-1">{{ $journal->voucher_type }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong class="text-muted d-block text-sm">Narration</strong>
                        {{ $journal->narration ?: '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong class="text-muted d-block text-sm">Created By</strong>
                        {{ $journal->user->name ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Entry Items</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th width="15%">Code</th>
                            <th width="30%">Account</th>
                            <th class="text-right" width="20%">Debit (&#2547;)</th>
                            <th class="text-right" width="20%">Credit (&#2547;)</th>
                            <th width="15%">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalDebit = 0; $totalCredit = 0; @endphp
                        @foreach($journal->items as $item)
                        @php $totalDebit += $item->debit; $totalCredit += $item->credit; @endphp
                        <tr>
                            <td><strong>{{ $item->account->code ?? '' }}</strong></td>
                            <td>{{ $item->account->name ?? '' }}</td>
                            <td class="text-right {{ $item->debit > 0 ? 'text-success font-weight-bold' : '' }}">
                                {{ $item->debit > 0 ? number_format($item->debit, 2) : '' }}
                            </td>
                            <td class="text-right {{ $item->credit > 0 ? 'text-danger font-weight-bold' : '' }}">
                                {{ $item->credit > 0 ? number_format($item->credit, 2) : '' }}
                            </td>
                            <td class="text-muted">{{ $item->description ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="thead-dark">
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="text-right">&#2547; {{ number_format($totalDebit, 2) }}</th>
                            <th class="text-right">&#2547; {{ number_format($totalCredit, 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if($totalDebit == $totalCredit)
            <div class="card-footer">
                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Balanced</span>
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
