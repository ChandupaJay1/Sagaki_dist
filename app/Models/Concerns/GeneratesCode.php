<?php

namespace App\Models\Concerns;

trait GeneratesCode
{
    public static function bootGeneratesCode(): void
    {
        static::creating(function ($model) {
            if (empty($model->code) && !empty($model->name)) {
                $model->code = static::generateUniqueCodeFromName($model->name);
            }
        });

        static::updating(function ($model) {
            if (empty($model->code) && !empty($model->name)) {
                $model->code = static::generateUniqueCodeFromName($model->name);
            }
        });
    }

    protected static function generateUniqueCodeFromName(string $name): string
    {
        $base = strtoupper(preg_replace('/[^A-Z]/', '', implode('', array_map(fn($p) => substr($p, 0, 1), preg_split('/\s+/', trim($name))))));
        if ($base === '') {
            $base = strtoupper(substr(preg_replace('/\s+/', '', $name), 0, 3));
        }
        $base = substr($base, 0, 4);

        $code = $base;
        $i = 1;
        while (static::query()->where('code', $code)->exists()) {
            $code = $base . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
            $i++;
        }
        return $code;
    }
}

