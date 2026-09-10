<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLivroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->ehBibliotecario() ?? false;
    }

    public function rules(): array
    {
        $livro = $this->route('livro');

        return [
            'titulo' => ['sometimes', 'required', 'string', 'max:255'],
            'isbn' => ['sometimes', 'required', 'string', Rule::unique('livros', 'isbn')->ignore($livro)],
            'ano_publicacao' => ['nullable', 'integer', 'min:1400', 'max:' . (date('Y') + 1)],
            'capa_url' => ['nullable', 'url'],
            'autor_id' => ['sometimes', 'required', 'exists:autores,id'],
            'categorias' => ['nullable', 'array'],
            'categorias.*' => ['exists:categorias,id'],
        ];
    }
}
