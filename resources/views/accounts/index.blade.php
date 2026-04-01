@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-calculator mr-2"></i>Chart of Accounts</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('accounts.create') }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus mr-1"></i> Add Account
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Accounts</h3>
            </div>
            <div class="card-body">
                <table id="accountsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Group</th>
                            <th class="text-right">Opening Balance</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $account)
                        <tr>
                            <td><strong>{{ $account->code }}</strong></td>
                            <td>{{ $account->name }}</td>
                            <td><span class="badge badge-info text-capitalize">{{ $account->type }}</span></td>
                            <td>{{ $account->group?->name ?? '—' }}</td>
                            <td class="text-right">&#2547; {{ number_format($account->opening_balance, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('accounts.edit', $account) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('accounts.ledger', $account) }}" class="btn btn-sm btn-outline-success" title="Ledger"><i class="fas fa-book"></i></a>
                                <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
<script>$(function(){ $('#accountsTable').DataTable({ responsive: true, autoWidth: false, order: [[0, 'asc']], dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>tp', buttons: ['copy', 'excel', 'print'] }); });</script>
@endpush
