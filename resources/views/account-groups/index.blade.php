@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-layer-group mr-2"></i>Account Groups</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('account-groups.create') }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus mr-1"></i> Add Account Group
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Account Groups</h3>
            </div>
            <div class="card-body">
                <table id="accountGroupsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Parent Group</th>
                            <th>Sub Groups</th>
                            <th>Accounts</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $group)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $group->name }}</strong></td>
                            <td><span class="badge badge-info text-capitalize">{{ $group->type }}</span></td>
                            <td>{{ $group->parent?->name ?? '—' }}</td>
                            <td><span class="badge badge-secondary">{{ $group->children->count() }}</span></td>
                            <td><span class="badge badge-secondary">{{ $group->accounts->count() }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('account-groups.edit', $group) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('account-groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will also delete all sub groups.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
<script>
$(function(){
    $('#accountGroupsTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[2, 'asc'], [1, 'asc']],
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>tp',
        buttons: ['copy', 'excel', 'print']
    });
});
</script>
@endpush
