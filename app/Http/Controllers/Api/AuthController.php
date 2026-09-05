<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());

        return response()->json($this->respostaComToken($user), 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $dados = $request->validated();
        $user = User::query()->where('email', $dados['email'])->first();

        if ($user === null || ! Hash::check($dados['password'], $user->password)) {
            return response()->json(['message' => 'E-mail ou senha inválidos.'], 401);
        }

        return response()->json($this->respostaComToken($user));
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /** @return array{access_token: string, token_type: string, user: User} */
    private function respostaComToken(User $user): array
    {
        return [
            'access_token' => $user->criarToken(),
            'token_type' => 'bearer',
            'user' => $user,
        ];
    }
}
