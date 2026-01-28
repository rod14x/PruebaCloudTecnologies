<?php

namespace App\Http\Requests;

use App\Enums\PrioridadTicket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string', 'max:1000'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'prioridad' => ['required', Rule::enum(PrioridadTicket::class)],
            'evidencia' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede exceder 255 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'prioridad.required' => 'Debe seleccionar una prioridad.',
            'evidencia.file' => 'La evidencia debe ser un archivo.',
            'evidencia.mimes' => 'La evidencia debe ser una imagen (JPEG, JPG, PNG o GIF).',
            'evidencia.max' => 'La evidencia no puede exceder 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'titulo' => 'título',
            'descripcion' => 'descripción',
            'categoria_id' => 'categoría',
            'prioridad' => 'prioridad',
            'evidencia' => 'evidencia',
        ];
    }
}
