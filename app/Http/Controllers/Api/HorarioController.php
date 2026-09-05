<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Horario::class);

        $horarios = Horario::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->get();

        return response()->json($horarios);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Horario::class);
        $dados = $this->dadosValidos($request);
        $userId = $request->user()->id;

        if (Horario::existeConflito($userId, $dados['data'], $dados['hora_inicio'], $dados['hora_fim'])) {
            return response()->json([
                'message' => 'Já existe um horário nesse intervalo.',
            ], 422);
        }

        $horario = Horario::query()->create([
            ...$dados,
            'user_id' => $userId,
            'status' => $dados['status'] ?? 'agendado',
        ]);

        return response()->json($horario, 201);
    }

    public function show(Request $request, Horario $horario): JsonResponse
    {
        $this->authorize('view', $horario);

        return response()->json($horario);
    }

    public function update(Request $request, Horario $horario): JsonResponse
    {
        $this->authorize('update', $horario);
        $dados = $this->dadosValidos($request);

        if (Horario::existeConflito(
            $request->user()->id,
            $dados['data'],
            $dados['hora_inicio'],
            $dados['hora_fim'],
            $horario->id,
        )) {
            return response()->json([
                'message' => 'Já existe um horário nesse intervalo.',
            ], 422);
        }

        $horario->update($dados);

        return response()->json($horario);
    }

    public function destroy(Request $request, Horario $horario): JsonResponse
    {
        $this->authorize('delete', $horario);
        $horario->delete();

        return response()->json(status: 204);
    }

    /** @return array<string, mixed> */
    private function dadosValidos(Request $request): array
    {
        return $request->validate([
            'cliente' => ['required', 'string', 'max:120'],
            'data' => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fim' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'status' => ['sometimes', 'in:agendado,confirmado,cancelado,concluido'],
            'observacao' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
