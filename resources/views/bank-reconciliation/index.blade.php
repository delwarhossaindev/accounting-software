@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1><i class="fas fa-university mr-2"></i>Bank Reconciliation</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Filter --}}
        <div class="card card-outline card-info">
            <div class="card-header"><h3 class="card-title">Filter</h3></div>
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2">Account</label>
                        <select name="account_id" class="form-control">
                            @foreach($bankAccounts as $a)
                                <option value="{{ $a->id }}" @selected($accountId == $a->id)>{{ $a->code }} — {{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">From</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">To</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i>Load</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($stmtIn, 2) }}</h3>
                        <p>Statement Deposits</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-down"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($stmtOut, 2) }}</h3>
                        <p>Statement Withdrawals</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-up"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($bookBalance, 2) }}</h3>
                        <p>Book Balance (GL)</p>
                    </div>
                    <div class="icon"><i class="fas fa-book"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box {{ abs($stmtNet - $bookBalance) < 0.01 ? 'bg-success' : 'bg-warning' }}">
                    <div class="inner">
                        <h3>&#2547; {{ number_format($stmtNet - $bookBalance, 2) }}</h3>
                        <p>Difference</p>
                    </div>
                    <div class="icon"><i class="fas fa-balance-scale"></i></div>
                </div>
            </div>
        </div>

        {{-- Import & Add Line --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Import Statement (CSV)</h3></div>
                    <div class="card-body">
                        <p class="text-muted">CSV columns: date, description, reference, debit, credit, balance</p>
                        <form method="POST" action="{{ route('bank-reconciliation.import') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="account_id" value="{{ $accountId }}">
                            <div class="input-group">
                                <input type="file" name="file" accept=".csv" class="form-control" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-info"><i class="fas fa-upload mr-1"></i>Import</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Add Statement Line Manually</h3></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('bank-reconciliation.line.store') }}">
                            @csrf
                            <input type="hidden" name="account_id" value="{{ $accountId }}">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <input type="date" name="date" class="form-control" required value="{{ now()->toDateString() }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <input type="text" name="description" placeholder="Description" class="form-control" required>
                                </div>
                                <div class="col-md-2 form-group">
                                    <input type="number" step="0.01" name="debit" placeholder="Debit" class="form-control">
                                </div>
                                <div class="col-md-2 form-group">
                                    <input type="number" step="0.01" name="credit" placeholder="Credit" class="form-control">
                                </div>
                                <div class="col-md-1 form-group">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lines --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statement Lines ({{ $matchedCount }} matched, {{ $unmatchedCount }} unmatched)</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover m-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Ref</th>
                            <th class="text-right">Debit</th>
                            <th class="text-right">Credit</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lines as $line)
                        <tr class="{{ $line->status === 'matched' ? 'table-success' : ($line->status === 'ignored' ? 'text-muted' : '') }}">
                            <td>{{ $line->date->format('d M Y') }}</td>
                            <td>{{ $line->description }}</td>
                            <td>{{ $line->reference }}</td>
                            <td class="text-right">{{ $line->debit ? number_format($line->debit, 2) : '—' }}</td>
                            <td class="text-right">{{ $line->credit ? number_format($line->credit, 2) : '—' }}</td>
                            <td class="text-center">
                                <span class="badge badge-{{ $line->status === 'matched' ? 'success' : ($line->status === 'ignored' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst($line->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($line->status !== 'ignored')
                                <form method="POST" action="{{ route('bank-reconciliation.line.ignore', $line) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary" title="Ignore"><i class="fas fa-ban"></i></button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('bank-reconciliation.line.destroy', $line) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted p-3">No statement lines. Import a CSV or add manually.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Finalize --}}
        <div class="card card-outline card-success">
            <div class="card-header"><h3 class="card-title">Finalize Reconciliation</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('bank-reconciliation.finalize') }}">
                    @csrf
                    <input type="hidden" name="account_id" value="{{ $accountId }}">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="book_balance" value="{{ $bookBalance }}">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Statement Opening</label>
                            <input type="number" step="0.01" name="statement_opening" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Statement Closing</label>
                            <input type="number" step="0.01" name="statement_closing" class="form-control" value="{{ $stmtNet }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Notes</label>
                            <input type="text" name="notes" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check-circle mr-1"></i>Finalize</button>
                </form>
            </div>
        </div>

        @if($history->isNotEmpty())
        <div class="card">
            <div class="card-header"><h3 class="card-title">History</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped m-0">
                    <thead>
                        <tr><th>Period</th><th class="text-right">Opening</th><th class="text-right">Closing</th><th class="text-right">Book</th><th class="text-right">Diff</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($history as $h)
                        <tr>
                            <td>{{ $h->start_date->format('d M Y') }} → {{ $h->end_date->format('d M Y') }}</td>
                            <td class="text-right">{{ number_format($h->statement_opening, 2) }}</td>
                            <td class="text-right">{{ number_format($h->statement_closing, 2) }}</td>
                            <td class="text-right">{{ number_format($h->book_balance, 2) }}</td>
                            <td class="text-right">{{ number_format($h->difference, 2) }}</td>
                            <td><span class="badge badge-{{ $h->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($h->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection
