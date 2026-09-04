<?php

namespace Tests\Feature;

use App\Models\Horario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HorarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_detecta_horario_sobreposto(): void
    {
        $user = User::factory()->create();

        Horario::query()->create([
            'user_id' => $user->id,
            'cliente' => 'Ana',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
            'status' => 'agendado',
        ]);

        $this->assertTrue(
            Horario::existeConflito($user->id, '2026-09-10', '14:30', '15:30')
        );
    }

    public function test_permite_horario_em_seguida(): void
    {
        $user = User::factory()->create();

        Horario::query()->create([
            'user_id' => $user->id,
            'cliente' => 'Ana',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
            'status' => 'agendado',
        ]);

        $this->assertFalse(
            Horario::existeConflito($user->id, '2026-09-10', '15:00', '16:00')
        );
    }
}
