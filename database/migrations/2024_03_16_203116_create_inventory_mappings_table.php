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
        Schema::create('inventory_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_from');
            $table->integer('quantity_from');
            $table->integer('price_from');
            $table->unsignedBigInteger('product_to');
            $table->integer('quantity_to');
            $table->integer('price_to');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps(); 
            $table->softDeletes();

            $table->foreign('product_from')->references('id')->on('products');
            $table->foreign('product_to')->references('id')->on('products');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_mappings');
    }
};
