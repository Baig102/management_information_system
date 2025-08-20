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
        Schema::create('booking_hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->references('id')->on('bookings');
            //$table->foreignId('supplier_id')->references('id')->on('vendors');
            $table->string('supplier', 100)->nullable();
            $table->string('hotel_name', 100);
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('total_nights')->unsigned()->nullable();
            $table->string('meal_type', 100)->nullable();
            $table->string('room_type', 20)->nullable();
            $table->string('hotel_confirmation_number', 100)->nullable();
            $table->float('sale_cost', 15,2)->nullable();
            $table->float('net_cost', 15,2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->integer('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_hotels');
    }
};
