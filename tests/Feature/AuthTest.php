<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_devolve_token(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'password1',
        ])
            ->assertCreated()
            ->assertJsonStructure(['access_token', 'token_type', 'user' => ['id', 'email']]);
    }

    public function test_login_com_senha_errada_retorna_401(): void
    {
        User::factory()->create([
            'email' => 'ana@example.com',
            'password' => 'password1',
        ]);

        $this->postJson('/api/login', [
            'email' => 'ana@example.com',
            'password' => 'errada',
        ])->assertUnauthorized();
    }

    public function test_horarios_sem_token_retorna_401(): void
    {
        $this->getJson('/api/horarios')->assertUnauthorized();
    }

    public function test_profissional_cria_horario_com_token(): void
    {
        $token = $this->postJson('/api/register', [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'password1',
        ])->json('access_token');

        $this->postJson('/api/horarios', [
            'cliente' => 'Carlos',
            'data' => '2026-09-10',
            'hora_inicio' => '14:00',
            'hora_fim' => '15:00',
        ], ['Authorization' => "Bearer {$token}"])
            ->assertCreated()
            ->assertJsonPath('cliente', 'Carlos');
    }

    public function test_cadastro_com_email_invalido_retorna_422(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Ana',
            'email' => 'nao-e-email',
            'password' => 'password1',
        ])->assertUnprocessable();
    }

    public function test_me_devolve_profissional_autenticada(): void
    {
        $token = $this->postJson('/api/register', [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'password1',
        ])->json('access_token');

        $this->getJson('/api/me', ['Authorization' => "Bearer {$token}"])
            ->assertOk()
            ->assertJsonPath('email', 'ana@example.com')
            ->assertJsonMissingPath('password');
    }

    public function test_login_devolve_token(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'password1',
        ]);

        $this->postJson('/api/login', [
            'email' => 'ana@example.com',
            'password' => 'password1',
        ])
            ->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'user' => ['id', 'email']]);
    }
}
