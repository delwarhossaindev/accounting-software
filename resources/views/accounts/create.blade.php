@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-plus-circle mr-2"></i>Create Account</h1></div>
            <div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Accounts</a></li><li class="breadcrumb-item active">Create</li></ol></div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-primary card-outline">
                    <div class="card-header"><h3 class="card-title">Account Information</h3></div>
                    <form action="{{ route('accounts.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code">Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                                        @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type <span class="text-danger">*</span></label>
                                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                            <option value="">Select Type</option>
                                            @foreach(['asset','liability','equity','income','expense'] as $type)
                                            <option value="{{ $type }}" @selected(old('type')===$type)>{{ ucfirst($type) }}</option>
                                            @endforeach
                                        </select>
                                        @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_group_id">Account Group</label>
                                        <select name="account_group_id" id="account_group_id" class="form-control">
                                            <option value="">None</option>
                                            @foreach($groups as $group)
                                            <option value="{{ $group->id }}" @selected(old('account_group_id')==$group->id)>{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="opening_balance">Opening Balance</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">&#2547;</span></div>
                                    <input type="number" name="opening_balance" id="opening_balance" class="form-control" value="{{ old('opening_balance', 0) }}" step="0.01">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Account</button>
                            <a href="{{ route('accounts.index') }}" class="btn btn-default ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
