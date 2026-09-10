<?php

namespace App\Http\Requests;

use App\Rules\IsbnValido;
use Illuminate\Foundation\Http\FormRequest;

class StoreLivroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->ehBibliotecario() ?? false;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'unique:livros,isbn', new IsbnValido()],
            'ano_publicacao' => ['nullable', 'integer', 'min:1400', 'max:' . (date('Y') + 1)],
            'capa_url' => ['nullable', 'url'],
            'autor_id' => ['required', 'exists:autores,id'],
            'categorias' => ['nullable', 'array'],
            'categorias.*' => ['exists:categorias,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.unique' => 'Já existe um livro cadastrado com esse ISBN.',
            'autor_id.exists' => 'Selecione um autor válido.',
        ];
    }
}
