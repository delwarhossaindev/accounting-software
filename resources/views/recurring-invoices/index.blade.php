@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-sync-alt mr-2"></i>Recurring Invoices</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('recurring-invoices.create') }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus mr-1"></i> New Recurring Invoice
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table id="tbl" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Party</th>
                            <th>Frequency</th>
                            <th>Next Run</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-center">Generated</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $r)
                        <tr>
                            <td><strong>{{ $r->name }}</strong></td>
                            <td><span class="badge badge-{{ $r->type === 'sales' ? 'success' : 'warning' }}">{{ $r->type }}</span></td>
                            <td>{{ $r->customer?->name ?? $r->supplier?->name ?? '—' }}</td>
                            <td>{{ ucfirst($r->frequency) }}</td>
                            <td>{{ $r->next_run_date->format('d M Y') }}</td>
                            <td class="text-right">&#2547; {{ number_format($r->subtotal, 2) }}</td>
                            <td class="text-center">{{ $r->generated_count }}</td>
                            <td class="text-center">
                                <span class="badge badge-{{ $r->is_active ? 'success' : 'secondary' }}">
                                    {{ $r->is_active ? 'Active' : 'Paused' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('recurring-invoices.edit', $r) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('recurring-invoices.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $items->links() }}
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>$(function(){ $('#tbl').DataTable({ responsive: true, paging: false, info: false }); });</script>
@endpush
