@extends('layouts.app')

@section('content')
@php
    $label = $type === 'credit' ? 'Credit Note' : 'Debit Note';
    $partyLabel = $type === 'credit' ? 'Customer' : 'Supplier';
    $icon = $type === 'credit' ? 'fa-undo' : 'fa-redo';
@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas {{ $icon }} mr-2"></i>{{ $label }}s</h1></div>
            <div class="col-sm-6 text-right">
                <div class="btn-group">
                    <a href="{{ route('credit-debit-notes.index', ['type' => 'credit']) }}" class="btn btn-sm {{ $type === 'credit' ? 'btn-primary' : 'btn-outline-primary' }}">Credit Notes</a>
                    <a href="{{ route('credit-debit-notes.index', ['type' => 'debit']) }}" class="btn btn-sm {{ $type === 'debit' ? 'btn-primary' : 'btn-outline-primary' }}">Debit Notes</a>
                </div>
                <a href="{{ route('credit-debit-notes.create', ['type' => $type]) }}" class="btn btn-primary ml-2">
                    <i class="fas fa-plus mr-1"></i> New {{ $label }}
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h3 class="card-title">All {{ $label }}s</h3></div>
            <div class="card-body">
                <table id="notesTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Note #</th>
                            <th>Date</th>
                            <th>{{ $partyLabel }}</th>
                            <th>Linked Invoice</th>
                            <th>Reason</th>
                            <th class="text-right">Total</th>
                            <th class="text-center" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notes as $note)
                        <tr>
                            <td><strong>{{ $note->note_no }}</strong></td>
                            <td>{{ $note->date->format('d M Y') }}</td>
                            <td>{{ $type === 'credit' ? ($note->customer?->name ?? '—') : ($note->supplier?->name ?? '—') }}</td>
                            <td>
                                @if($note->invoice)
                                    <a href="{{ route('invoices.show', $note->invoice) }}">{{ $note->invoice->invoice_no }}</a>
                                @else — @endif
                            </td>
                            <td>{{ $note->reason ?? '—' }}</td>
                            <td class="text-right">&#2547; {{ number_format($note->total, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('credit-debit-notes.show', $note) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('credit-debit-notes.destroy', $note) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this note? Stock will be reversed.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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
<script>$(function(){ $('#notesTable').DataTable({ responsive: true, order: [[1, 'desc']] }); });</script>
@endpush
