<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthenticateJwt
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if ($token === null) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        try {
            $payload = JWT::decode($token, new Key((string) config('app.key'), 'HS256'));
        } catch (Throwable) {
            return response()->json(['message' => 'Token inválido.'], 401);
        }

        $user = User::query()->find($payload->sub ?? null);

        if ($user === null) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        Auth::setUser($user);

        return $next($request);
    }
}
