<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadCapaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->ehBibliotecario() ?? false;
    }

    public function rules(): array
    {
        return [
            // 2048 KB = 2 MB. Tipos aceitos cobrem os formatos mais comuns de capa de livro.
            'capa' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'capa.image' => 'O arquivo enviado precisa ser uma imagem.',
            'capa.mimes' => 'Formatos aceitos: JPG, PNG ou WEBP.',
            'capa.max' => 'A imagem não pode passar de 2 MB.',
        ];
    }
}
