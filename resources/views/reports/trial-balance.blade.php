@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-balance-scale mr-2"></i>Trial Balance</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Trial Balance</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Filter --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.trial-balance') }}" method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="date" class="mr-2">As of Date</label>
                        <input type="date" name="date" id="date" value="{{ request('date', date('Y-m-d')) }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Generate</button>
                    <a href="{{ route('pdf.trial-balance', ['date' => request('date', date('Y-m-d'))]) }}" class="btn btn-secondary ml-2" target="_blank">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </a>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table mr-1"></i> Trial Balance as of {{ date('d M Y', strtotime(request('date', date('Y-m-d')))) }}</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Account Code</th>
                            <th>Account Name</th>
                            <th>Type</th>
                            <th class="text-right">Debit (&#2547;)</th>
                            <th class="text-right">Credit (&#2547;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                        <tr>
                            <td><strong>{{ $account['code'] }}</strong></td>
                            <td>{{ $account['name'] }}</td>
                            <td><span class="badge badge-info text-capitalize">{{ $account['type'] }}</span></td>
                            <td class="text-right">{{ $account['debit'] > 0 ? number_format($account['debit'], 2) : '' }}</td>
                            <td class="text-right">{{ $account['credit'] > 0 ? number_format($account['credit'], 2) : '' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No data available.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="thead-dark">
                        <tr>
                            <th colspan="3">Total</th>
                            <th class="text-right">&#2547; {{ number_format($totalDebit, 2) }}</th>
                            <th class="text-right">&#2547; {{ number_format($totalCredit, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if($totalDebit == $totalCredit)
            <div class="card-footer">
                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Balanced - Debit equals Credit</span>
            </div>
            @else
            <div class="card-footer">
                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Not Balanced - Difference: &#2547; {{ number_format(abs($totalDebit - $totalCredit), 2) }}</span>
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
