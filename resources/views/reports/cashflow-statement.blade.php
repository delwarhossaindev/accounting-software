@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-water mr-2"></i>{{ __('messages.cashflow_statement') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Cashflow</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2">From</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">To</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Generate</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($opening, 2) }}</h3>
                        <p>Opening Cash Balance</p>
                    </div>
                    <div class="icon"><i class="fas fa-door-open"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box {{ $netChange >= 0 ? 'bg-success' : 'bg-danger' }}">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($netChange, 2) }}</h3>
                        <p>Net Change in Cash</p>
                    </div>
                    <div class="icon"><i class="fas fa-exchange-alt"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($closing, 2) }}</h3>
                        <p>Closing Cash Balance</p>
                    </div>
                    <div class="icon"><i class="fas fa-door-closed"></i></div>
                </div>
            </div>
        </div>

        @foreach(['operating' => ['Operating Activities', 'success', $operating, $netOperating],
                  'investing' => ['Investing Activities', 'warning', $investing, $netInvesting],
                  'financing' => ['Financing Activities', 'purple', $financing, $netFinancing]] as $key => $section)
        <div class="card card-outline card-{{ $section[1] }}">
            <div class="card-header">
                <h3 class="card-title">{{ $section[0] }}</h3>
                <div class="card-tools">
                    <span class="badge badge-{{ $section[1] }}">Net &#2547; {{ number_format($section[3], 2) }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover m-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Narration</th>
                            <th class="text-right">Inflow (&#2547;)</th>
                            <th class="text-right">Outflow (&#2547;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($section[2]['items'] as $it)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($it['date'])->format('d M Y') }}</td>
                            <td>{{ $it['account'] }}</td>
                            <td>{{ $it['narration'] }}</td>
                            <td class="text-right text-success">{{ $it['inflow'] ? number_format($it['inflow'], 2) : '—' }}</td>
                            <td class="text-right text-danger">{{ $it['outflow'] ? number_format($it['outflow'], 2) : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted">No transactions</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="3">Total</th>
                            <th class="text-right">{{ number_format($section[2]['in'], 2) }}</th>
                            <th class="text-right">{{ number_format($section[2]['out'], 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endforeach

    </div>
</section>
@endsection
