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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('item_code')->nullable();
            $table->string('description')->nullable();
            $table->double('weight')->nullable();
            $table->integer('min_quantity')->nullable();
            $table->integer('max_quantity')->nullable();
            $table->double('stock')->nullable();

            $table->json('images')->nullable();
            $table->json('damage_images')->nullable();

            $table->string('comments')->nullable();
            $table->string('status')->nullable();

            //relations

            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreignId('location_id')->references('id')->on('locations')->onDelete('cascade');


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
