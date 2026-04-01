@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-key mr-2"></i>Permissions</h1></div>
            <div class="col-sm-6">
                @can('settings.permissions.create')
                    <a href="{{ route('settings.permissions.create') }}" class="btn btn-primary float-sm-right">
                        <i class="fas fa-plus mr-1"></i> Add Permission
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Permissions</h3>
            </div>
            <div class="card-body">
                <table id="permissionsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td><strong>{{ $permission->name }}</strong></td>
                                <td class="text-center">
                                    @can('settings.permissions.edit')
                                        <a href="{{ route('settings.permissions.edit', $permission) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('settings.permissions.delete')
                                        <form action="{{ route('settings.permissions.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
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
<script>
    $(function () {
        $('#permissionsTable').DataTable({ responsive: true, autoWidth: false, order: [[0, 'asc']] });
    });
</script>
@endpush

