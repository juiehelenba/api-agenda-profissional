<?php

namespace App\Policies;

use App\Models\Horario;
use App\Models\User;

class HorarioPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Horario $horario): bool
    {
        return $user->id === $horario->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Horario $horario): bool
    {
        return $user->id === $horario->user_id;
    }

    public function delete(User $user, Horario $horario): bool
    {
        return $user->id === $horario->user_id;
    }
}
