<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livro_id')->constrained('livros');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->date('data_reserva');
            $table->enum('status', ['ativa', 'atendida', 'cancelada'])->default('ativa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
