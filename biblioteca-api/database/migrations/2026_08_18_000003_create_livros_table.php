<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('isbn')->unique();
            $table->year('ano_publicacao')->nullable();
            $table->string('capa_path')->nullable(); // caminho no disco (storage/app/public/...)
            $table->string('capa_url')->nullable();  // URL pública já resolvida, pronta pro front-end
            $table->foreignId('autor_id')->constrained('autores');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
