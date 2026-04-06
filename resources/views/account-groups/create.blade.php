@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-plus-circle mr-2"></i>Create Account Group</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('account-groups.index') }}">Account Groups</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Account Group Information</h3>
                    </div>
                    <form action="{{ route('account-groups.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Group Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter group name" required>
                                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type <span class="text-danger">*</span></label>
                                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                            <option value="">Select Type</option>
                                            @foreach($types as $type)
                                            <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
                                            @endforeach
                                        </select>
                                        @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="parent_id">Parent Group</label>
                                <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                                    <option value="">None (Root Group)</option>
                                    @foreach($parentGroups as $parent)
                                    <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>
                                        {{ $parent->name }} ({{ ucfirst($parent->type) }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('parent_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Group</button>
                            <a href="{{ route('account-groups.index') }}" class="btn btn-default ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
