@extends('layouts.app')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-tachometer-alt mr-2 text-primary"></i>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">

        {{-- Cash & Bank Cards --}}
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($cashInHand, 2) }}</h3>
                        <p>Cash in Hand</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <a href="{{ route('accounts.index') }}" class="small-box-footer">
                        Account #1001 <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($cashAtBank, 2) }}</h3>
                        <p>Cash at Bank</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <a href="{{ route('accounts.index') }}" class="small-box-footer">
                        Account #1002 <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- 4 Info Boxes --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-invoice"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Receivable</span>
                        <span class="info-box-number">&#2547; {{ number_format($totalReceivable, 2) }}</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-warning" style="width: 100%"></div>
                        </div>
                        <span class="progress-description text-sm">{{ $totalInvoices }} invoices total</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Payable</span>
                        <span class="info-box-number">&#2547; {{ number_format($totalPayable, 2) }}</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-danger" style="width: 100%"></div>
                        </div>
                        <span class="progress-description text-sm">{{ $totalBills }} bills total</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chart-line"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Income</span>
                        <span class="info-box-number">&#2547; {{ number_format($totalIncome, 2) }}</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                        <span class="progress-description text-sm">Revenue earned</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-receipt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Expense</span>
                        <span class="info-box-number">&#2547; {{ number_format($totalExpense, 2) }}</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-purple" style="width: 100%"></div>
                        </div>
                        <span class="progress-description text-sm">Total spending</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="row">
            {{-- Area Chart --}}
            <div class="col-lg-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-area mr-1"></i> Income vs Expense (Last 6 Months)</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="areaChart"></div>
                    </div>
                </div>
            </div>

            {{-- Donut Chart --}}
            <div class="col-lg-4">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Invoice Status</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="donutChart"></div>
                        <div class="row mt-3">
                            @php
                                $statusBadges = [
                                    'paid' => 'success', 'partial' => 'warning', 'sent' => 'info',
                                    'draft' => 'secondary', 'overdue' => 'danger', 'cancelled' => 'dark',
                                ];
                            @endphp
                            @foreach($invoiceStatusCounts as $status => $count)
                            <div class="col-6 mb-2">
                                <span class="badge badge-{{ $statusBadges[$status] ?? 'secondary' }} mr-1">{{ $count }}</span>
                                <small class="text-muted">{{ ucfirst($status) }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Expense Bar + Quick Stats --}}
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-purple card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Top Expense Categories</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="expenseBarChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Quick Stats</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('customers.index') }}">
                                    <i class="fas fa-users text-primary mr-3"></i>
                                    Customers
                                    <span class="badge bg-primary float-right ml-auto">{{ $customerCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('suppliers.index') }}">
                                    <i class="fas fa-building text-teal mr-3"></i>
                                    Suppliers
                                    <span class="badge bg-teal float-right ml-auto">{{ $supplierCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('invoices.index', ['type' => 'sales']) }}">
                                    <i class="fas fa-file-invoice text-info mr-3"></i>
                                    Sales Invoices
                                    <span class="badge bg-info float-right ml-auto">{{ $totalInvoices }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('invoices.index', ['type' => 'purchase']) }}">
                                    <i class="fas fa-shopping-cart text-warning mr-3"></i>
                                    Purchase Bills
                                    <span class="badge bg-warning float-right ml-auto">{{ $totalBills }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="#">
                                    <i class="fas fa-exclamation-triangle text-danger mr-3"></i>
                                    Overdue Invoices
                                    <span class="badge bg-danger float-right ml-auto">{{ $overdueInvoices }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Tables --}}
        <div class="row">
            {{-- Recent Sales Invoices --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><i class="fas fa-file-invoice mr-1 text-primary"></i> Recent Sales Invoices</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Invoice#</th>
                                        <th>Customer</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentInvoices as $invoice)
                                    <tr>
                                        <td><strong>{{ $invoice->invoice_no }}</strong></td>
                                        <td>{{ $invoice->customer->name ?? '-' }}</td>
                                        <td class="text-right">&#2547; {{ number_format($invoice->total, 2) }}</td>
                                        <td class="text-center">
                                            @php
                                                $badge = match($invoice->status) {
                                                    'paid' => 'success',
                                                    'partial' => 'warning',
                                                    'sent' => 'info',
                                                    'overdue' => 'danger',
                                                    'cancelled' => 'dark',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $badge }}">{{ ucfirst($invoice->status) }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">No invoices yet</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('invoices.index', ['type' => 'sales']) }}" class="text-sm">View All Invoices <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>

            {{-- Recent Payments --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><i class="fas fa-money-check-alt mr-1 text-success"></i> Recent Payments</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Payment#</th>
                                        <th>Date</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $payment)
                                    <tr>
                                        <td><strong>{{ $payment->payment_no }}</strong></td>
                                        <td>{{ $payment->date->format('d M Y') }}</td>
                                        <td class="text-center">
                                            @if($payment->type === 'received')
                                                <span class="badge badge-success"><i class="fas fa-arrow-down mr-1"></i>Received</span>
                                            @else
                                                <span class="badge badge-danger"><i class="fas fa-arrow-up mr-1"></i>Made</span>
                                            @endif
                                        </td>
                                        <td class="text-right font-weight-bold {{ $payment->type === 'received' ? 'text-success' : 'text-danger' }}">
                                            {{ $payment->type === 'received' ? '+' : '-' }} &#2547; {{ number_format($payment->amount, 2) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">No payments yet</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('payments.index', ['type' => 'received']) }}" class="text-sm">View All Payments <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Expenses --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-receipt mr-1 text-purple"></i> Recent Expenses</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Expense#</th>
                                        <th>Date</th>
                                        <th>Account</th>
                                        <th>Description</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentExpenses as $expense)
                                    <tr>
                                        <td><strong>{{ $expense->expense_no }}</strong></td>
                                        <td>{{ $expense->date->format('d M Y') }}</td>
                                        <td><span class="badge badge-light">{{ $expense->account->name }}</span></td>
                                        <td class="text-muted">{{ Str::limit($expense->description, 40) ?? '-' }}</td>
                                        <td class="text-right font-weight-bold">&#2547; {{ number_format($expense->amount, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center text-muted py-3">No expenses yet</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('expenses.index') }}" class="text-sm">View All Expenses <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
$(function () {
    // Area Chart
    var monthlyData = @json($monthlyData);
    new ApexCharts(document.querySelector("#areaChart"), {
        chart: { type: 'area', height: 350, toolbar: { show: false }, fontFamily: 'Source Sans Pro, sans-serif' },
        series: [
            { name: 'Income', data: monthlyData.map(d => d.income) },
            { name: 'Expense', data: monthlyData.map(d => d.expense) }
        ],
        xaxis: {
            categories: monthlyData.map(d => d.month),
            labels: { style: { fontSize: '12px' } }
        },
        yaxis: {
            labels: { formatter: v => '৳' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v) }
        },
        colors: ['#28a745', '#dc3545'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 80, 100] }
        },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f1f1' },
        tooltip: { y: { formatter: v => '৳ ' + v.toLocaleString() } },
        legend: { position: 'top', horizontalAlign: 'right', fontSize: '13px', fontWeight: 600 }
    }).render();

    // Donut Chart
    var statusCounts = @json($invoiceStatusCounts);
    var statusLabels = { paid: 'Paid', partial: 'Partial', sent: 'Sent', draft: 'Draft', overdue: 'Overdue', cancelled: 'Cancelled' };
    var statusColors = { paid: '#28a745', partial: '#ffc107', sent: '#17a2b8', draft: '#6c757d', overdue: '#dc3545', cancelled: '#343a40' };

    new ApexCharts(document.querySelector("#donutChart"), {
        chart: { type: 'donut', height: 250, fontFamily: 'Source Sans Pro, sans-serif' },
        series: Object.values(statusCounts),
        labels: Object.keys(statusCounts).map(k => statusLabels[k] || k),
        colors: Object.keys(statusCounts).map(k => statusColors[k] || '#6c757d'),
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true, label: 'Total', fontSize: '14px', fontWeight: 700,
                            formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        stroke: { width: 2, colors: ['#fff'] }
    }).render();

    // Expense Bar Chart
    var topExpenses = @json($topExpenses->map(fn($e) => ['name' => $e->account->name ?? 'N/A', 'total' => $e->total]));
    new ApexCharts(document.querySelector("#expenseBarChart"), {
        chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Source Sans Pro, sans-serif' },
        series: [{ name: 'Amount', data: topExpenses.map(e => e.total) }],
        xaxis: {
            categories: topExpenses.map(e => e.name),
            labels: { style: { fontSize: '11px' }, trim: true, maxHeight: 60 }
        },
        yaxis: {
            labels: { formatter: v => '৳' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v) }
        },
        colors: ['#6f42c1', '#007bff', '#20c997', '#fd7e14', '#e83e8c'],
        plotOptions: { bar: { borderRadius: 6, columnWidth: '55%', distributed: true } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f1f1' },
        legend: { show: false },
        tooltip: { y: { formatter: v => '৳ ' + Number(v).toLocaleString() } }
    }).render();
});
</script>
@endpush
