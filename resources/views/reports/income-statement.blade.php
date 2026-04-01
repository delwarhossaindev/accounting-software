@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-chart-line mr-2"></i>Income Statement</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Income Statement</li>
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
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Date Range</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.income-statement') }}" method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="start_date" class="mr-2">From</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date', date('Y-01-01')) }}" class="form-control">
                    </div>
                    <div class="form-group mr-3">
                        <label for="end_date" class="mr-2">To</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date', date('Y-m-d')) }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Generate</button>
                    <a href="{{ route('pdf.income-statement', ['start_date' => request('start_date', date('Y-01-01')), 'end_date' => request('end_date', date('Y-m-d'))]) }}" class="btn btn-secondary ml-2" target="_blank">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </a>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                {{-- Income --}}
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-arrow-down text-success mr-1"></i> Income</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover m-0">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th class="text-right">Amount (&#2547;)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incomeAccounts as $account)
                                <tr>
                                    <td>{{ $account['name'] }}</td>
                                    <td class="text-right text-success font-weight-bold">{{ number_format($account['amount'], 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center text-muted">No income records</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-success">
                                <tr>
                                    <th class="text-white">Total Income</th>
                                    <th class="text-right text-white">&#2547; {{ number_format($totalIncome, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                {{-- Expenses --}}
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-arrow-up text-danger mr-1"></i> Expenses</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover m-0">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th class="text-right">Amount (&#2547;)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenseAccounts as $account)
                                <tr>
                                    <td>{{ $account['name'] }}</td>
                                    <td class="text-right text-danger font-weight-bold">{{ number_format($account['amount'], 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center text-muted">No expense records</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-danger">
                                <tr>
                                    <th class="text-white">Total Expenses</th>
                                    <th class="text-right text-white">&#2547; {{ number_format($totalExpenses, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Net Income --}}
        <div class="card {{ $netIncome >= 0 ? 'card-success' : 'card-danger' }}">
            <div class="card-body d-flex justify-content-between align-items-center py-4">
                <h3 class="m-0">
                    <i class="fas {{ $netIncome >= 0 ? 'fa-chart-line' : 'fa-chart-line fa-flip-vertical' }} mr-2"></i>
                    Net {{ $netIncome >= 0 ? 'Profit' : 'Loss' }}
                </h3>
                <h2 class="m-0 font-weight-bold">&#2547; {{ number_format(abs($netIncome), 2) }}</h2>
            </div>
        </div>

    </div>
</section>
@endsection
