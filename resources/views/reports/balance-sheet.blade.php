@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-chart-pie mr-2"></i>Balance Sheet</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Balance Sheet</li>
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
                <form action="{{ route('reports.balance-sheet') }}" method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="date" class="mr-2">As of Date</label>
                        <input type="date" name="date" id="date" value="{{ request('date', date('Y-m-d')) }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Generate</button>
                    <a href="{{ route('pdf.balance-sheet', ['date' => request('date', date('Y-m-d'))]) }}" class="btn btn-secondary ml-2" target="_blank">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </a>
                </form>
            </div>
        </div>

        {{-- Assets --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-coins text-primary mr-1"></i> Assets</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover m-0">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th class="text-right">Balance (&#2547;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $account)
                        <tr>
                            <td>{{ $account['name'] }}</td>
                            <td class="text-right font-weight-bold">{{ number_format($account['balance'], 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted">No asset accounts</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-primary">
                        <tr>
                            <th class="text-white">Total Assets</th>
                            <th class="text-right text-white">&#2547; {{ number_format($totalAssets, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                {{-- Liabilities --}}
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-hand-holding-usd text-warning mr-1"></i> Liabilities</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover m-0">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th class="text-right">Balance (&#2547;)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($liabilities as $account)
                                <tr>
                                    <td>{{ $account['name'] }}</td>
                                    <td class="text-right font-weight-bold">{{ number_format($account['balance'], 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center text-muted">No liability accounts</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-warning">
                                <tr>
                                    <th>Total Liabilities</th>
                                    <th class="text-right">&#2547; {{ number_format($totalLiabilities, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                {{-- Equity --}}
                <div class="card card-outline card-purple">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-landmark text-purple mr-1"></i> Equity</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover m-0">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th class="text-right">Balance (&#2547;)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($equity as $account)
                                <tr>
                                    <td>{{ $account['name'] }}</td>
                                    <td class="text-right font-weight-bold">{{ number_format($account['balance'], 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center text-muted">No equity accounts</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-purple">
                                <tr>
                                    <th class="text-white">Total Equity</th>
                                    <th class="text-right text-white">&#2547; {{ number_format($totalEquity, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total L+E --}}
        <div class="card card-dark">
            <div class="card-body d-flex justify-content-between align-items-center py-4">
                <h3 class="m-0"><i class="fas fa-equals mr-2"></i>Total Liabilities + Equity</h3>
                <h2 class="m-0 font-weight-bold">&#2547; {{ number_format($totalLiabilities + $totalEquity, 2) }}</h2>
            </div>
            @if($totalAssets == ($totalLiabilities + $totalEquity))
            <div class="card-footer">
                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Balanced - Assets = Liabilities + Equity</span>
            </div>
            @else
            <div class="card-footer">
                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Not Balanced - Difference: &#2547; {{ number_format(abs($totalAssets - ($totalLiabilities + $totalEquity)), 2) }}</span>
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
