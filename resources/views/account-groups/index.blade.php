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
                            <td>
                                @if($group->children->count() > 0)
                                    <button type="button"
                                        class="badge badge-primary border-0"
                                        style="cursor: pointer;"
                                        data-toggle="modal"
                                        data-target="#subGroupsModal"
                                        data-title="Sub Groups of {{ $group->name }}"
                                        data-items='@json($group->children->map(fn($c) => ["name" => $c->name, "type" => $c->type]))'>
                                        {{ $group->children->count() }}
                                    </button>
                                @else
                                    <span class="badge badge-secondary">0</span>
                                @endif
                            </td>
                            <td>
                                @if($group->accounts->count() > 0)
                                    <button type="button"
                                        class="badge badge-success border-0"
                                        style="cursor: pointer;"
                                        data-toggle="modal"
                                        data-target="#accountsModal"
                                        data-title="Accounts in {{ $group->name }}"
                                        data-items='@json($group->accounts->map(fn($a) => ["code" => $a->code, "name" => $a->name, "type" => $a->type]))'>
                                        {{ $group->accounts->count() }}
                                    </button>
                                @else
                                    <span class="badge badge-secondary">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('account-groups.edit', $group) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('account-groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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

<!-- Sub Groups Modal -->
<div class="modal fade" id="subGroupsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white"><i class="fas fa-sitemap mr-2"></i><span id="subGroupsTitle"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Type</th></tr>
                    </thead>
                    <tbody id="subGroupsBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Accounts Modal -->
<div class="modal fade" id="accountsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white"><i class="fas fa-book mr-2"></i><span id="accountsTitle"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr><th>Code</th><th>Name</th><th>Type</th></tr>
                    </thead>
                    <tbody id="accountsBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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

    $('#subGroupsModal').on('show.bs.modal', function (e) {
        const btn = $(e.relatedTarget);
        const items = btn.data('items') || [];
        $('#subGroupsTitle').text(btn.data('title'));
        let html = '';
        items.forEach((item, i) => {
            html += `<tr><td>${i + 1}</td><td><strong>${item.name}</strong></td><td><span class="badge badge-info text-capitalize">${item.type}</span></td></tr>`;
        });
        $('#subGroupsBody').html(html);
    });

    $('#accountsModal').on('show.bs.modal', function (e) {
        const btn = $(e.relatedTarget);
        const items = btn.data('items') || [];
        $('#accountsTitle').text(btn.data('title'));
        let html = '';
        items.forEach(item => {
            html += `<tr><td><strong>${item.code}</strong></td><td>${item.name}</td><td><span class="badge badge-info text-capitalize">${item.type}</span></td></tr>`;
        });
        $('#accountsBody').html(html);
    });
});
</script>
@endpush
