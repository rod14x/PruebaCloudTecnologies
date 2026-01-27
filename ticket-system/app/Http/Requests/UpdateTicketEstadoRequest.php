<?php

namespace App\Http\Requests;

use App\Enums\EstadoTicket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketEstadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo admins pueden cambiar el estado
        return auth()->check() && auth()->user()->esAdministrador();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'estado' => ['required', Rule::enum(EstadoTicket::class)],
            'comentario' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'estado.required' => 'El estado es obligatorio.',
            'comentario.max' => 'El comentario no puede exceder 500 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'estado' => 'estado',
            'comentario' => 'comentario',
        ];
    }
}
