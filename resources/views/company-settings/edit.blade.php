@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-building mr-2"></i>Company Settings</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Company Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('company-settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row">
                <div class="col-lg-8">
                    {{-- Company Information --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Company Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Company Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $company->name) }}" required>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone) }}" placeholder="+880 1xxx...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="text" name="website" class="form-control" value="{{ old('website', $company->website) }}" placeholder="https://...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>TIN</label>
                                        <input type="text" name="tin" class="form-control" value="{{ old('tin', $company->tin) }}" placeholder="Tax ID">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>BIN / VAT No.</label>
                                        <input type="text" name="bin" class="form-control" value="{{ old('bin', $company->bin) }}" placeholder="Business ID">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" rows="3" class="form-control" placeholder="Full address">{{ old('address', $company->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Financial Settings --}}
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-coins mr-1"></i> Financial Settings</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Currency Code</label>
                                        <input type="text" name="currency_code" class="form-control" value="{{ old('currency_code', $company->currency_code) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Currency Symbol</label>
                                        <input type="text" name="currency_symbol" class="form-control" value="{{ old('currency_symbol', $company->currency_symbol) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fiscal Year Starts</label>
                                        <select name="fiscal_year_start_month" class="form-control" required>
                                            @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $month)
                                                <option value="{{ $i + 1 }}" @selected(old('fiscal_year_start_month', $company->fiscal_year_start_month) == ($i + 1))>{{ $month }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sales Invoice Prefix</label>
                                        <input type="text" name="invoice_prefix" class="form-control" value="{{ old('invoice_prefix', $company->invoice_prefix) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purchase Bill Prefix</label>
                                        <input type="text" name="bill_prefix" class="form-control" value="{{ old('bill_prefix', $company->bill_prefix) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Invoice Footer --}}
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-file-invoice mr-1"></i> Invoice Footer & Terms</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Invoice Footer Note</label>
                                <textarea name="invoice_footer" rows="2" class="form-control" placeholder="e.g. N.B No Warranty for Broken, Sticker removes, Burning & Physical Damage">{{ old('invoice_footer', $company->invoice_footer) }}</textarea>
                                <small class="text-muted">Shown at the bottom of invoice PDF</small>
                            </div>
                            <div class="form-group">
                                <label>Terms & Conditions</label>
                                <textarea name="terms_conditions" rows="4" class="form-control" placeholder="Payment terms, return policy...">{{ old('terms_conditions', $company->terms_conditions) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Logo --}}
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-image mr-1"></i> Company Logo</h3>
                        </div>
                        <div class="card-body text-center">
                            @if($company->logo_path)
                                <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo" class="img-fluid mb-3" style="max-height: 150px; border: 1px solid #e2e8f0; padding: 10px; border-radius: 10px;">
                                <div class="form-group text-left">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="remove_logo" name="remove_logo" value="1">
                                        <label class="custom-control-label text-danger" for="remove_logo">Remove current logo</label>
                                    </div>
                                </div>
                            @else
                                <div class="text-muted mb-3" style="padding: 40px 0;">
                                    <i class="fas fa-image" style="font-size: 64px; opacity: 0.3;"></i>
                                    <p class="mt-2">No logo uploaded</p>
                                </div>
                            @endif
                            <div class="form-group text-left">
                                <label>Upload Logo</label>
                                <input type="file" name="logo" class="form-control-file" accept=".png,.jpg,.jpeg,.svg">
                                <small class="text-muted">PNG, JPG, SVG up to 2MB</small>
                            </div>
                        </div>
                    </div>

                    {{-- Branches Shortcut --}}
                    <div class="card">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-store mr-1"></i> Branches</h5>
                            <p class="text-muted text-sm">Manage Head Office + multiple branches</p>
                            <a href="{{ route('branches.index') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-arrow-right mr-1"></i> Manage Branches
                            </a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-save mr-1"></i> Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
