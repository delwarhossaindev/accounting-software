@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-file-invoice mr-2"></i>{{ $type == 'sales' ? 'Sales Invoices' : 'Purchase Bills' }}</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('invoices.create', ['type' => $type]) }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus mr-1"></i> Create New
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All {{ $type == 'sales' ? 'Invoices' : 'Bills' }}</h3>
            </div>
            <div class="card-body">
                <table id="invoicesTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>{{ $type == 'sales' ? 'Customer' : 'Supplier' }}</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Paid</th>
                            <th class="text-right">Due</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td><strong>{{ $invoice->invoice_no }}</strong></td>
                            <td>{{ $invoice->date->format('d M Y') }}</td>
                            <td>
                                @if($type == 'sales')
                                    {{ $invoice->customer->name ?? '—' }}
                                @else
                                    {{ $invoice->supplier->name ?? '—' }}
                                @endif
                            </td>
                            <td class="text-right">&#2547; {{ number_format($invoice->total, 2) }}</td>
                            <td class="text-right">&#2547; {{ number_format($invoice->paid, 2) }}</td>
                            <td class="text-right font-weight-bold {{ $invoice->due > 0 ? 'text-danger' : 'text-success' }}">&#2547; {{ number_format($invoice->due, 2) }}</td>
                            <td class="text-center">
                                @php
                                    $badge = match($invoice->status) {
                                        'paid' => 'success', 'partial' => 'warning', 'sent' => 'info',
                                        'overdue' => 'danger', 'cancelled' => 'dark', default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ ucfirst($invoice->status) }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('pdf.invoice', $invoice) }}" class="btn btn-sm btn-outline-secondary" title="PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>$(function(){ $('#invoicesTable').DataTable({ responsive: true, autoWidth: false, order: [[1, 'desc']], dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>tp', buttons: ['copy', 'excel', 'print'] }); });</script>
@endpush
