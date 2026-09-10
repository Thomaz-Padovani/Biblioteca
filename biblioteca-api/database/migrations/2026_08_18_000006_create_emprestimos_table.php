<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emprestimos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livro_id')->constrained('livros');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->date('data_emprestimo');
            $table->date('data_devolucao_prevista');
            $table->date('data_devolucao_real')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emprestimos');
    }
};
