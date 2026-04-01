@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-user-plus mr-2"></i>Add Customer</h1></div>
            <div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li><li class="breadcrumb-item active">Add</li></ol></div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row"><div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">Customer Information</h3></div>
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                        @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" rows="3" class="form-control">{{ old('address') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="opening_balance">Opening Balance</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">&#2547;</span></div>
                                <input type="number" step="0.01" name="opening_balance" id="opening_balance" class="form-control" value="{{ old('opening_balance', '0.00') }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Customer</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-default ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div></div>
    </div>
</section>
@endsection
