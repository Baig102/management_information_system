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
        Schema::create('booking_transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->references('id')->on('bookings');
            //$table->foreignId('supplier_id')->references('id')->on('vendors');
            $table->string('supplier', 100)->nullable();
            $table->string('transport_type', 100)->nullable();
            //$table->foreignId('airport_id')->references('id')->on('airports');
            $table->string('airport', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->time('time')->nullable();
            $table->string('car_type', 100)->nullable();
            $table->float('sale_cost', 15, 2)->nullable();
            $table->float('net_cost', 15, 2)->nullable();
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
        Schema::dropIfExists('booking_transports');
    }
};
