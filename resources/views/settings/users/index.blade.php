@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-users-cog mr-2"></i>Users</h1></div>
            <div class="col-sm-6">
                @can('settings.users.create')
                    <a href="{{ route('settings.users.create') }}" class="btn btn-primary float-sm-right">
                        <i class="fas fa-plus mr-1"></i> Add User
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
                <h3 class="card-title">All Users</h3>
            </div>
            <div class="card-body">
                <table id="usersTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge badge-info">{{ $role->name }}</span>
                                    @empty
                                        —
                                    @endforelse
                                </td>
                                <td class="text-center">
                                    @can('settings.users.edit')
                                        <a href="{{ route('settings.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('settings.users.delete')
                                        <form action="{{ route('settings.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
        $('#usersTable').DataTable({ responsive: true, autoWidth: false, order: [[0, 'asc']] });
    });
</script>
@endpush

