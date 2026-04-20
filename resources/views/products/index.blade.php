@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-boxes mr-2"></i>Products & Inventory</h1></div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.stock-report') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar mr-1"></i> Stock Report
                </a>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Add Product
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Products</h3>
            </div>
            <div class="card-body">
                <table id="productsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th class="text-right">Purchase</th>
                            <th class="text-right">Sale</th>
                            <th class="text-center">Stock</th>
                            <th class="text-right">Stock Value</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><strong>{{ $product->sku }}</strong></td>
                            <td>
                                {{ $product->name }}
                                @if(!$product->is_active)
                                    <span class="badge badge-secondary ml-1">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $product->category ?? '—' }}</td>
                            <td><span class="badge badge-light">{{ $product->unit }}</span></td>
                            <td class="text-right">&#2547; {{ number_format($product->purchase_price, 2) }}</td>
                            <td class="text-right">&#2547; {{ number_format($product->sale_price, 2) }}</td>
                            <td class="text-center">
                                @php
                                    $status = $product->stock_status;
                                    $badgeClass = match($status) {
                                        'out_of_stock' => 'danger',
                                        'low_stock' => 'warning',
                                        default => 'success',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">
                                    {{ rtrim(rtrim(number_format($product->current_stock, 2), '0'), '.') }} {{ $product->unit }}
                                </span>
                            </td>
                            <td class="text-right">&#2547; {{ number_format($product->stock_value, 2) }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#adjustStockModal{{ $product->id }}" title="Adjust Stock">
                                    <i class="fas fa-sliders-h"></i>
                                </button>
                                <a href="{{ route('products.movements', $product) }}" class="btn btn-sm btn-outline-success" title="Stock Movements">
                                    <i class="fas fa-history"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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

<!-- Adjust Stock Modals -->
@foreach($products as $product)
<div class="modal fade" id="adjustStockModal{{ $product->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('products.adjust-stock', $product) }}" method="POST">
                @csrf
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white"><i class="fas fa-sliders-h mr-2"></i>Adjust Stock: {{ $product->name }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light">
                        Current Stock: <strong>{{ rtrim(rtrim(number_format($product->current_stock, 2), '0'), '.') }} {{ $product->unit }}</strong>
                    </div>
                    <div class="form-group">
                        <label>Movement Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-control no-select2" required>
                            <option value="in">Stock In (Add)</option>
                            <option value="out">Stock Out (Remove)</option>
                            <option value="adjustment">Set Exact Quantity (Adjustment)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="quantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Reason for adjustment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info"><i class="fas fa-save mr-1"></i> Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
$(function(){
    $('#productsTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>tp',
        buttons: ['copy', 'excel', 'print']
    });
});
</script>
@endpush
