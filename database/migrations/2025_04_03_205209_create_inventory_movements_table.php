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
//        Schema::create('inventory_movements', function (Blueprint $table) {
//            $table->id();
//            $table->string('comments')->nullable();
//            $table->foreignId('created_by')->references('id')->on('users')->onDelete('cascade');
//            $table->foreignId('item_id')->references('id')->on('items')->onDelete('cascade');
//            $table->foreignId('inventory_outbound_id')->references('id')->on('inventory_outbounds')->onDelete('cascade')->nullable();
//            $table->foreignId('inventory_entry_id')->references('id')->on('inventory_entries')->onDelete('cascade')->nullable();
//            $table->double('quantity');
//            $table->string('status');
//            $table->softDeletes();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
