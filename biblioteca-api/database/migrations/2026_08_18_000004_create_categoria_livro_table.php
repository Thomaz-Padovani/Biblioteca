<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categoria_livro', function (Blueprint $table) {
            $table->foreignId('livro_id')->constrained('livros')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->primary(['livro_id', 'categoria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_livro');
    }
};
