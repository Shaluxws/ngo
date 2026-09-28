<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    protected static array $sensitiveKeys = [
        'password',
        'password_confirmation',
        'remember_token',
        'app_key',
        'secret',
        'api_key',
        'token',
    ];

    public static function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        mixed $oldValues = null,
        mixed $newValues = null,
        ?int $userId = null
    ): ?AuditLog {
        try {
            $user = $userId ?: Auth::id();

            $sanitizedOld = self::sanitize($oldValues);
            $sanitizedNew = self::sanitize($newValues);

            return AuditLog::create([
                'user_id' => $user,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'old_values' => $sanitizedOld,
                'new_values' => $sanitizedNew,
                'ip_address' => Request::ip(),
                'user_agent' => substr(Request::userAgent() ?? '', 0, 500),
            ]);
        } catch (\Throwable $e) {
            // Fail safely without breaking the main transaction if logging hits an edge case
            report($e);
            return null;
        }
    }

    protected static function sanitize(mixed $data): ?array
    {
        if (is_null($data)) {
            return null;
        }

        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        } elseif (!is_array($data)) {
            return ['value' => (string) $data];
        }

        $sanitized = [];
        foreach ($data as $key => $value) {
            if (in_array(strtolower((string)$key), self::$sensitiveKeys, true)) {
                $sanitized[$key] = '********';
            } elseif (is_array($value)) {
                $sanitized[$key] = self::sanitize($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }
}
