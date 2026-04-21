@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1><i class="fas fa-sync-alt mr-2"></i>Edit Recurring Invoice</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('recurring-invoices.update', $item) }}">
                    @method('PUT')
                    @include('recurring-invoices._form')
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
