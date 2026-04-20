@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-percent mr-2"></i>Tax Rates</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('tax-rates.create') }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus mr-1"></i> Add Tax Rate
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h3 class="card-title">All Tax Rates</h3></div>
            <div class="card-body">
                <table id="taxTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="text-center">Rate (%)</th>
                            <th class="text-center">Default</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taxRates as $tax)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $tax->name }}</strong></td>
                            <td class="text-center"><span class="badge badge-info">{{ number_format($tax->rate, 2) }}%</span></td>
                            <td class="text-center">
                                @if($tax->is_default)
                                    <span class="badge badge-success"><i class="fas fa-star mr-1"></i>Default</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($tax->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('tax-rates.edit', $tax) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('tax-rates.destroy', $tax) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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
<script>$(function(){ $('#taxTable').DataTable({ responsive: true, order: [[1, 'asc']] }); });</script>
@endpush
