<?php

namespace Database\Factories;

use App\Models\Horario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Horario>
 */
class HorarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'cliente' => fake()->name(),
            'data' => fake()->dateTimeBetween('+1 day', '+1 month')->format('Y-m-d'),
            'hora_inicio' => '09:00',
            'hora_fim' => '10:00',
            'status' => 'agendado',
            'observacao' => null,
        ];
    }
}
