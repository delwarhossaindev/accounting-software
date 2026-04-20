@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-chart-bar mr-2"></i>Stock Report</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Stock Report</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        {{-- Summary Cards --}}
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($totalStockValue, 2) }}</h3>
                        <p>Total Stock Value</p>
                    </div>
                    <div class="icon"><i class="fas fa-warehouse"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $lowStock }}</h3>
                        <p>Low Stock Items</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $outOfStock }}</h3>
                        <p>Out of Stock</p>
                    </div>
                    <div class="icon"><i class="fas fa-times-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Current Stock Status</h3>
            </div>
            <div class="card-body">
                <table id="stockTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-center">Current Stock</th>
                            <th class="text-center">Reorder Level</th>
                            <th class="text-right">Purchase Price</th>
                            <th class="text-right">Stock Value</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><strong>{{ $product->sku }}</strong></td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category ?? '—' }}</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format($product->current_stock, 2), '0'), '.') }} {{ $product->unit }}</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format($product->reorder_level, 2), '0'), '.') }}</td>
                            <td class="text-right">&#2547; {{ number_format($product->purchase_price, 2) }}</td>
                            <td class="text-right font-weight-bold">&#2547; {{ number_format($product->stock_value, 2) }}</td>
                            <td class="text-center">
                                @php
                                    $status = $product->stock_status;
                                    $badgeClass = match($status) {
                                        'out_of_stock' => 'danger',
                                        'low_stock' => 'warning',
                                        default => 'success',
                                    };
                                    $statusLabel = match($status) {
                                        'out_of_stock' => 'Out of Stock',
                                        'low_stock' => 'Low Stock',
                                        default => 'In Stock',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="6" class="text-right">Total Stock Value:</th>
                            <th class="text-right">&#2547; {{ number_format($totalStockValue, 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(function(){
    $('#stockTable').DataTable({
        responsive: true,
        order: [[1, 'asc']],
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>tp',
        buttons: ['copy', 'excel', 'print']
    });
});
</script>
@endpush
