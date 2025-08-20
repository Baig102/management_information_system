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
        Schema::create('booking_visas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->references('id')->on('bookings');
            $table->string('supplier', 100)->nullable();
            $table->string('visa_category', 100)->nullable();
            $table->string('visa_country', 100)->nullable();
            $table->integer('no_of_pax')->unsigned()->nullable();
            $table->string('nationality', 100)->nullable();
            $table->float('sale_cost', 15,2)->nullable();
            $table->float('net_cost', 15,2)->nullable();
            $table->float('actual_net_cost', 15,2)->nullable();
            $table->string('remarks', 255)->nullable();
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
        Schema::dropIfExists('booking_visas');
    }
};
