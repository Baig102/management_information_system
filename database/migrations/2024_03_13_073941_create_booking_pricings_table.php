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
        Schema::create('booking_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->references('id')->on('bookings');
            $table->string('booking_type', 50)->nullable();
            $table->float('sale_cost', 15, 2)->nullable();
            $table->float('net_cost', 15, 2)->nullable();
            $table->integer('quantity')->unsigned()->nullable();
            $table->float('total', 15, 2)->nullable();
            $table->float('net_total', 15, 2)->nullable();
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
        Schema::dropIfExists('booking_pricings');
    }
};
