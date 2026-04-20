@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-file-signature mr-2"></i>Quotations</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('quotations.create') }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus mr-1"></i> Create Quotation
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h3 class="card-title">All Quotations</h3></div>
            <div class="card-body">
                <table id="quotationsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Quote #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Subject</th>
                            <th>Valid Until</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotations as $q)
                        <tr>
                            <td><strong>{{ $q->quotation_no }}</strong></td>
                            <td>{{ $q->date->format('d M Y') }}</td>
                            <td>{{ $q->customer?->name ?? '—' }}</td>
                            <td>{{ $q->subject ?? '—' }}</td>
                            <td>{{ $q->valid_until?->format('d M Y') ?? '—' }}</td>
                            <td class="text-right">&#2547; {{ number_format($q->total, 2) }}</td>
                            <td class="text-center">
                                @php
                                    $badge = match($q->status) {
                                        'draft' => 'secondary', 'sent' => 'info', 'accepted' => 'success',
                                        'rejected' => 'danger', 'expired' => 'dark', 'converted' => 'primary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ ucfirst($q->status) }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('quotations.show', $q) }}" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                                @if($q->status !== 'converted')
                                    <form action="{{ route('quotations.convert', $q) }}" method="POST" class="d-inline" onsubmit="return confirm('Convert this quotation to an invoice?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Convert to Invoice"><i class="fas fa-exchange-alt"></i></button>
                                    </form>
                                    <form action="{{ route('quotations.destroy', $q) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this quotation?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                @else
                                    <a href="{{ route('invoices.show', $q->converted_invoice_id) }}" class="btn btn-sm btn-outline-primary" title="Go to Invoice"><i class="fas fa-arrow-right"></i></a>
                                @endif
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
<script>$(function(){ $('#quotationsTable').DataTable({ responsive: true, order: [[1, 'desc']] }); });</script>
@endpush
