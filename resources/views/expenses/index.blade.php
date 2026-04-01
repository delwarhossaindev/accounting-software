@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-receipt mr-2"></i>Expenses</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('pdf.expenses') }}" class="btn btn-secondary float-sm-right mr-2" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="{{ route('expenses.create') }}" class="btn btn-primary float-sm-right mr-2">
                    <i class="fas fa-plus mr-1"></i> Add Expense
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Expenses</h3>
            </div>
            <div class="card-body">
                <table id="expensesTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Expense No</th>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Supplier</th>
                            <th class="text-right">Amount</th>
                            <th>Category</th>
                            <th>Method</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                        <tr>
                            <td><strong>{{ $expense->expense_no }}</strong></td>
                            <td>{{ $expense->date->format('d M Y') }}</td>
                            <td>{{ $expense->account->name ?? '—' }}</td>
                            <td>{{ $expense->supplier->name ?? '—' }}</td>
                            <td class="text-right font-weight-bold text-danger">&#2547; {{ number_format($expense->amount, 2) }}</td>
                            <td><span class="badge badge-info text-capitalize">{{ $expense->category ?? '—' }}</span></td>
                            <td><span class="badge badge-light text-capitalize">{{ str_replace('_', ' ', $expense->payment_method) }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
<script>$(function(){ $('#expensesTable').DataTable({ responsive: true, autoWidth: false, order: [[1, 'desc']], dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>tp', buttons: ['copy', 'excel', 'print'] }); });</script>
@endpush
