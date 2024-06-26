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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ['sale', 'purchase'])->nullable();
            $table->integer('transaction_type_id')->nullable();
            $table->date('transaction_date');
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_model','255')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_type', ['credit', 'cash'])->nullable();
            $table->text('comment')->nullable();
            $table->boolean('is_check')->nullable()->default(false);
            $table->boolean('is_cancel')->nullable()->default(false);
            $table->boolean('is_complete')->nullable()->default(false);

            $table->unsignedBigInteger('transaction_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_by')->references('id')->on('users');
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
        Schema::dropIfExists('transactions');
    }
};
