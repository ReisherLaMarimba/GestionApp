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
        Schema::create('item_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Relación con ítems
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relación con usuarios
            $table->softDeletes(); // Habilita el uso de la columna deleted_at para marcar registros como borrados
            $table->timestamps(); // Timestamps para created_at y updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_user');
    }
};
