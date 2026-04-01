@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-users mr-2"></i>Customers</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('pdf.customers') }}" class="btn btn-secondary float-sm-right mr-2" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="{{ route('customers.create') }}" class="btn btn-primary float-sm-right mr-2">
                    <i class="fas fa-plus mr-1"></i> Add Customer
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Customers</h3>
            </div>
            <div class="card-body">
                <table id="customersTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="text-right">Opening Balance</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td><strong>{{ $customer->name }}</strong></td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td class="text-right">&#2547; {{ number_format($customer->opening_balance, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
<script>$(function(){ $('#customersTable').DataTable({ responsive: true, autoWidth: false, dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>tp', buttons: ['copy', 'excel', 'print'] }); });</script>
@endpush
