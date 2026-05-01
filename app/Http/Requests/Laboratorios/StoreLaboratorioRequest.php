<?php

namespace App\Http\Requests\Laboratorios;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaboratorioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'nome' => ['required', 'string', 'max:255', 'unique:laboratorios,nome'],

                'capacidade' => ['required', 'integer', 'min:1'], 
                'tipo' => ['required', 'string', 'max:255'],
                'localizacao' => ['required', 'string', 'max:255'],
            ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'capacidade.required' => 'O campo capacidade é obrigatório.',
            'tipo.required' => 'O campo tipo é obrigatório.',
            'localizacao.required' => 'O campo localização é obrigatório.',

            'nome.string' => 'O campo nome deve ser uma string.',
            'capacidade.integer' => 'O campo capacidade deve ser um inteiro.',
            'tipo.string' => 'O campo tipo deve ser uma string.',
            'localizacao.string' => 'O campo localização deve ser uma string.',
        ];
    }
}
