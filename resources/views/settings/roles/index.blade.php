@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-user-shield mr-2"></i>Roles</h1></div>
            <div class="col-sm-6">
                @can('settings.roles.create')
                    <a href="{{ route('settings.roles.create') }}" class="btn btn-primary float-sm-right">
                        <i class="fas fa-plus mr-1"></i> Add Role
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
                <h3 class="card-title">All Roles</h3>
            </div>
            <div class="card-body">
                <table id="rolesTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Users</th>
                            <th>Permissions</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td><strong>{{ $role->name }}</strong></td>
                                <td class="text-center">{{ $role->users_count }}</td>
                                <td>
                                    @foreach($role->permissions as $permission)
                                        <span class="badge badge-secondary">{{ $permission->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @can('settings.roles.edit')
                                        <a href="{{ route('settings.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('settings.roles.delete')
                                        <form action="{{ route('settings.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" @disabled($role->name === 'Admin')>
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
        $('#rolesTable').DataTable({ responsive: true, autoWidth: false, order: [[0, 'asc']] });
    });
</script>
@endpush

