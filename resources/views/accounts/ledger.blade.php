@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-book mr-2"></i>Ledger: {{ $account->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Accounts</a></li>
                    <li class="breadcrumb-item active">Ledger</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Account Info --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-hashtag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Account Code</span>
                        <span class="info-box-number">{{ $account->code }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-tag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Type</span>
                        <span class="info-box-number text-capitalize">{{ $account->type }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-layer-group"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Group</span>
                        <span class="info-box-number">{{ $account->group?->name ?? '—' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-purple">
                    <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Opening Balance</span>
                        <span class="info-box-number">&#2547; {{ number_format($account->opening_balance, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Date Filter</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('accounts.ledger', $account) }}" method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="start_date" class="mr-2">From</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>
                    <div class="form-group mr-3">
                        <label for="end_date" class="mr-2">To</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Filter</button>
                    <a href="{{ route('accounts.ledger', $account) }}" class="btn btn-default ml-2"><i class="fas fa-redo mr-1"></i> Reset</a>
                </form>
            </div>
        </div>

        {{-- Ledger Table --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Ledger Entries</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>Voucher No</th>
                            <th>Description</th>
                            <th class="text-right">Debit (&#2547;)</th>
                            <th class="text-right">Credit (&#2547;)</th>
                            <th class="text-right">Balance (&#2547;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $balance = $runningBalance; @endphp
                        @forelse($entries as $entry)
                        @php
                            if (in_array($account->type, ['asset', 'expense'])) {
                                $balance += $entry->debit - $entry->credit;
                            } else {
                                $balance += $entry->credit - $entry->debit;
                            }
                        @endphp
                        <tr>
                            <td>{{ $entry->journalEntry->date->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('journals.show', $entry->journalEntry) }}" class="font-weight-bold">
                                    {{ $entry->journalEntry->voucher_no }}
                                </a>
                            </td>
                            <td>{{ $entry->description ?? '-' }}</td>
                            <td class="text-right {{ $entry->debit > 0 ? 'text-success font-weight-bold' : '' }}">
                                {{ $entry->debit > 0 ? number_format($entry->debit, 2) : '' }}
                            </td>
                            <td class="text-right {{ $entry->credit > 0 ? 'text-danger font-weight-bold' : '' }}">
                                {{ $entry->credit > 0 ? number_format($entry->credit, 2) : '' }}
                            </td>
                            <td class="text-right font-weight-bold {{ $balance < 0 ? 'text-danger' : '' }}">
                                {{ number_format(abs($balance), 2) }} {{ $balance < 0 ? 'Cr' : 'Dr' }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No entries found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('accounts.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Chart of Accounts
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
