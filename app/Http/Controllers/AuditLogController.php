<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct(
        private AuditLogRepositoryInterface $logs,
        private UserRepositoryInterface $users,
    ) {}

    public function index(Request $request)
    {
        $query = $this->logs->query()->with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(50)->withQueryString();

        $users = $this->users->all([], ['name' => 'asc']);
        $modelTypes = $this->logs->query()->distinct()->pluck('model_type')->filter()->sort()->values();

        return view('audit-logs.index', compact('logs', 'users', 'modelTypes'));
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        return view('audit-logs.show', compact('auditLog'));
    }
}
