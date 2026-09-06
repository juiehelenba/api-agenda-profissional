<?php

namespace Database\Seeders;

use App\Models\Horario;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $ana = User::factory()->create([
            'name' => 'Ana Souza',
            'email' => 'ana@example.com',
            'password' => 'password1',
        ]);

        Horario::factory()->for($ana)->create([
            'cliente' => 'Carlos Mendes',
            'data' => '2026-09-10',
            'hora_inicio' => '09:00',
            'hora_fim' => '10:00',
        ]);

        Horario::factory()->for($ana)->create([
            'cliente' => 'Diana Costa',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
        ]);

        Horario::factory()->for($ana)->create([
            'cliente' => 'Eduardo Lima',
            'data' => '2026-09-11',
            'hora_inicio' => '10:00',
            'hora_fim' => '11:00',
            'status' => 'confirmado',
        ]);
    }
}
