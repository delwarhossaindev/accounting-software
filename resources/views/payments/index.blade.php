@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-money-check-alt mr-2"></i>{{ $type == 'received' ? 'Payments Received' : 'Payments Made' }}</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('payments.create', ['type' => $type]) }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus mr-1"></i> Record Payment
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All {{ $type == 'received' ? 'Received' : 'Made' }} Payments</h3>
            </div>
            <div class="card-body">
                <table id="paymentsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Payment No</th>
                            <th>Date</th>
                            <th>{{ $type == 'received' ? 'Customer' : 'Supplier' }}</th>
                            <th>Invoice</th>
                            <th class="text-right">Amount</th>
                            <th>Method</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td><strong>{{ $payment->payment_no }}</strong></td>
                            <td>{{ $payment->date->format('d M Y') }}</td>
                            <td>
                                @if($type == 'received')
                                    {{ $payment->customer->name ?? '—' }}
                                @else
                                    {{ $payment->supplier->name ?? '—' }}
                                @endif
                            </td>
                            <td>{{ $payment->invoice->invoice_no ?? '—' }}</td>
                            <td class="text-right font-weight-bold {{ $type == 'received' ? 'text-success' : 'text-danger' }}">
                                &#2547; {{ number_format($payment->amount, 2) }}
                            </td>
                            <td><span class="badge badge-light text-capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span></td>
                            <td class="text-center">
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="type" value="{{ $type }}">
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
<script>$(function(){ $('#paymentsTable').DataTable({ responsive: true, autoWidth: false, order: [[1, 'desc']], dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>tp', buttons: ['copy', 'excel', 'print'] }); });</script>
@endpush
