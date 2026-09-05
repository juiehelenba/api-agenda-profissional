<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HorarioAutorizacaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_nao_acessa_horario_de_outra_profissional(): void
    {
        $tokenAna = $this->token('ana@example.com');
        $tokenBia = $this->token('bia@example.com');

        $id = $this->postJson('/api/horarios', [
            'cliente' => 'Carlos',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
        ], $this->bearer($tokenAna))->json('id');

        $this->getJson("/api/horarios/{$id}", $this->bearer($tokenBia))
            ->assertForbidden();
    }

    public function test_recusa_horario_sobreposto(): void
    {
        $token = $this->token('ana@example.com');
        $headers = $this->bearer($token);

        $this->postJson('/api/horarios', [
            'cliente' => 'Carlos',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
        ], $headers)->assertCreated();

        $this->postJson('/api/horarios', [
            'cliente' => 'Diana',
            'data' => '2026-09-10',
            'hora_inicio' => '14:30',
            'hora_fim' => '15:30',
        ], $headers)
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Já existe um horário nesse intervalo.');
    }

    private function token(string $email): string
    {
        return $this->postJson('/api/register', [
            'name' => 'Profissional',
            'email' => $email,
            'password' => 'password1',
        ])->json('access_token');
    }

    /** @return array<string, string> */
    private function bearer(string $token): array
    {
        return ['Authorization' => "Bearer {$token}"];
    }
}
