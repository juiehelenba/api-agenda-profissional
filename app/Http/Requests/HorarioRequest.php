<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HorarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'cliente' => ['required', 'string', 'max:120'],
            'data' => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fim' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'status' => ['sometimes', 'in:agendado,confirmado,cancelado,concluido'],
            'observacao' => ['nullable', 'string', 'max:500'],
        ];
    }
}
