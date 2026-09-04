<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('cliente');
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->string('status')->default('agendado');
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
