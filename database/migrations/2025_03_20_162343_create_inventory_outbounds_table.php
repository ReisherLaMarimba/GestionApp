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
        Schema::create('inventory_outbounds', function (Blueprint $table) {
            $table->id();

            $table->double('quantity');
            $table->string('comments')->nullable();
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('cascade');
            //$table->unsignedBigInteger('inventory_outbound_id');

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
        Schema::dropIfExists('inventory_outbounds');
    }
};
