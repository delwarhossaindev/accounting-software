@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-book mr-2"></i>Journal Entries</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('journals.create') }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus mr-1"></i> New Entry
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Journal Entries</h3>
            </div>
            <div class="card-body">
                <table id="journalsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Voucher No</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Narration</th>
                            <th class="text-right">Total Amount</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $entry)
                        <tr>
                            <td><strong>{{ $entry->voucher_no }}</strong></td>
                            <td>{{ $entry->date->format('d M Y') }}</td>
                            <td>
                                @php
                                    $typeBadge = match($entry->voucher_type) {
                                        'journal' => 'primary', 'receipt' => 'success', 'payment' => 'danger',
                                        'contra' => 'warning', 'sales' => 'info', 'purchase' => 'secondary',
                                        default => 'light',
                                    };
                                @endphp
                                <span class="badge badge-{{ $typeBadge }} text-capitalize">{{ $entry->voucher_type }}</span>
                            </td>
                            <td class="text-truncate" style="max-width: 250px;">{{ $entry->narration }}</td>
                            <td class="text-right font-weight-bold">&#2547; {{ number_format($entry->total_amount, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('pdf.journal', $entry) }}" class="btn btn-sm btn-outline-secondary" title="PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <a href="{{ route('journals.show', $entry) }}" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('journals.destroy', $entry) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
<script>$(function(){ $('#journalsTable').DataTable({ responsive: true, autoWidth: false, order: [[1, 'desc']], dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>tp', buttons: ['copy', 'excel', 'print'] }); });</script>
@endpush
