<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HorarioCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_horarios_paginada(): void
    {
        $headers = $this->bearer($this->token('ana@example.com'));

        $this->postJson('/api/horarios', [
            'cliente' => 'Carlos',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
        ], $headers)->assertCreated();

        $this->getJson('/api/horarios', $headers)
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonPath('data.0.cliente', 'Carlos');
    }

    public function test_atualiza_horario(): void
    {
        $headers = $this->bearer($this->token('ana@example.com'));

        $id = $this->postJson('/api/horarios', [
            'cliente' => 'Carlos',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
        ], $headers)->json('id');

        $this->putJson("/api/horarios/{$id}", [
            'cliente' => 'Carlos Silva',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
            'status' => 'confirmado',
        ], $headers)
            ->assertOk()
            ->assertJsonPath('cliente', 'Carlos Silva')
            ->assertJsonPath('status', 'confirmado');
    }

    public function test_apaga_horario(): void
    {
        $headers = $this->bearer($this->token('ana@example.com'));

        $id = $this->postJson('/api/horarios', [
            'cliente' => 'Carlos',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
        ], $headers)->json('id');

        $this->deleteJson("/api/horarios/{$id}", [], $headers)->assertNoContent();
        $this->getJson("/api/horarios/{$id}", $headers)->assertNotFound();
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
