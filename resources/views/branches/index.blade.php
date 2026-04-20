@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-store mr-2"></i>Branches</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('branches.create') }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus mr-1"></i> Add Branch
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h3 class="card-title">All Branches</h3></div>
            <div class="card-body">
                <table id="branchTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th class="text-center">Head Office</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branches as $branch)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $branch->name }}</strong></td>
                            <td>{{ $branch->address ?? '—' }}</td>
                            <td>{{ $branch->phone ?? '—' }}</td>
                            <td>{{ $branch->email ?? '—' }}</td>
                            <td class="text-center">
                                @if($branch->is_head_office)
                                    <span class="badge badge-success"><i class="fas fa-star mr-1"></i>Head Office</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $branch->is_active ? 'success' : 'secondary' }}">
                                    {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
<script>$(function(){ $('#branchTable').DataTable({ responsive: true, order: [[1, 'asc']] }); });</script>
@endpush
