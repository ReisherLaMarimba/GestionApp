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
        Schema::create('inventory_entries', function (Blueprint $table) {
            $table->id();
            $table->double('quantity');
            $table->string('comments')->nullable();
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('source')->nullable(); // Ejemplo: "Compra", "Devolución"
            $table->string('document_reference')->nullable(); // Ejemplo: "OC-12345"
            $table->enum('entry_type', ['new', 'adjustment', 'return', 'correction'])->default('new')->nullable();
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('cascade');
//            $table->foreignId('asigned_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_entries');
    }
};
