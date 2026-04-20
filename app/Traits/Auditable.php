<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAudit($model, 'created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);
            if (empty($changes)) return;

            $original = [];
            foreach (array_keys($changes) as $key) {
                $original[$key] = $model->getOriginal($key);
            }

            self::logAudit($model, 'updated', $original, $changes);
        });

        static::deleted(function ($model) {
            self::logAudit($model, 'deleted', $model->getOriginal(), null);
        });
    }

    protected static function logAudit($model, string $action, $oldValues, $newValues): void
    {
        try {
            $modelName = class_basename($model);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'model_type' => $modelName,
                'model_id' => $model->id,
                'description' => ucfirst($action) . ' ' . $modelName . ' #' . $model->id,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
            ]);
        } catch (\Throwable $e) {
            // Never break the main transaction because of audit failure
        }
    }
}
