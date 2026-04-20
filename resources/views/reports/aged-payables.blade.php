@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-clock mr-2"></i>Aged Payables</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Aged Payables</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <form method="GET" class="form-inline">
                    <label class="mr-2">As of Date:</label>
                    <input type="date" name="as_of_date" class="form-control form-control-sm mr-2 no-select2" value="{{ $asOfDate }}">
                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-filter mr-1"></i> Filter</button>
                </form>
            </div>
            <div class="card-body">
                @if($report->isEmpty())
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle mr-2"></i> No outstanding payables as of this date.
                    </div>
                @else
                <table id="agedTable" class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Supplier</th>
                            <th class="text-right">Current (Not Due)</th>
                            <th class="text-right text-warning">1–30 Days</th>
                            <th class="text-right" style="color: #fd7e14;">31–60 Days</th>
                            <th class="text-right text-danger">61–90 Days</th>
                            <th class="text-right text-danger">Over 90 Days</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report as $row)
                        <tr>
                            <td><strong>{{ $row['supplier']->name }}</strong></td>
                            <td class="text-right">&#2547; {{ number_format($row['buckets']['current'], 2) }}</td>
                            <td class="text-right">&#2547; {{ number_format($row['buckets']['1_30'], 2) }}</td>
                            <td class="text-right">&#2547; {{ number_format($row['buckets']['31_60'], 2) }}</td>
                            <td class="text-right">&#2547; {{ number_format($row['buckets']['61_90'], 2) }}</td>
                            <td class="text-right">&#2547; {{ number_format($row['buckets']['over_90'], 2) }}</td>
                            <td class="text-right font-weight-bold">&#2547; {{ number_format($row['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th>TOTAL</th>
                            <th class="text-right">&#2547; {{ number_format($totals['current'], 2) }}</th>
                            <th class="text-right">&#2547; {{ number_format($totals['1_30'], 2) }}</th>
                            <th class="text-right">&#2547; {{ number_format($totals['31_60'], 2) }}</th>
                            <th class="text-right">&#2547; {{ number_format($totals['61_90'], 2) }}</th>
                            <th class="text-right">&#2547; {{ number_format($totals['over_90'], 2) }}</th>
                            <th class="text-right" style="font-size: 1.1rem;">&#2547; {{ number_format($totals['total'], 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>$(function(){ if($.fn.DataTable) $('#agedTable').DataTable({ responsive: true, paging: false, searching: true, order: [[6, 'desc']] }); });</script>
@endpush
