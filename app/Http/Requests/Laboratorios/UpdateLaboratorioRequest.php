<?php

namespace App\Http\Requests\Laboratorios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaboratorioRequest extends FormRequest
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
            // 1. REGRAS PARA O CAMPO 'nome'
            'nome' => [
                'required', 
                'string', 
                Rule::unique('laboratorios', 'nome')->ignore($this->route('laboratorio')),
            ], 
            
            // 2. REGRAS PARA O CAMPO 'capacidade'
            'capacidade' => ['required', 'integer', 'min:1'],
            
            // 3. REGRAS PARA O CAMPO 'tipo'
            'tipo' => ['required', 'string'],
            
            // 4. REGRAS PARA O CAMPO 'localizacao'
            'localizacao' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'capacidade.required' => 'O campo capacidade é obrigatório.',
            'tipo.required' => 'O campo tipo é obrigatório.',
            'localizacao.required' => 'O campo localização é obrigatório.',

            'nome.string' => 'O campo nome deve ser uma string.',
            'capacidade.integer' => 'O campo capacidade deve ser um inteiro.',
            'tipo.string' => 'O campo tipo deve ser uma string.',
            'localizacao.string' => 'O campo localização deve ser uma string.',
        ];
    }
}