<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teste', function (Blueprint $table) {
            $table->id();

            $table->string('coluna_legal');
            $table->integer('numero_foda');
            $table->text('text_num_muda_nada');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teste');
    }
};
