<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Exigida pelo driver de sessão "database" (ver .env: SESSION_DRIVER=database).
// Ensinado no Encontro 5 (Sessões no Laravel): o back-end roda em container
// no Render, sem disco persistente garantido, então a sessão precisa
// sobreviver num lugar estável — o banco.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
