<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }

    public function criarToken(): string
    {
        $agora = time();

        return JWT::encode([
            'sub' => $this->id,
            'iat' => $agora,
            'exp' => $agora + 60 * 60,
        ], (string) config('app.key'), 'HS256');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
