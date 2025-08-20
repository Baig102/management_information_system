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
        Schema::create('booking_installment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->references('id')->on('bookings');
            $table->integer('installment_number');
            $table->date('due_date');
            $table->float('amount', 15, 2)->nullable();
            $table->boolean('is_received')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
            $table->foreignId('received_by')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->dateTime('received_on')->nullable();
            $table->integer('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_installment_plans');
    }
};
