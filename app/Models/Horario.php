<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'cliente',
    'data',
    'hora_inicio',
    'hora_fim',
    'status',
    'observacao',
])]
class Horario extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function existeConflito(
        int $userId,
        string $data,
        string $inicio,
        string $fim,
        ?int $ignorarId = null,
    ): bool {
        return static::query()
            ->where('user_id', $userId)
            ->whereDate('data', $data)
            ->where('status', '!=', 'cancelado')
            ->when($ignorarId, fn ($q) => $q->where('id', '!=', $ignorarId))
            ->where('hora_inicio', '<', $fim)
            ->where('hora_fim', '>', $inicio)
            ->exists();
    }
}
