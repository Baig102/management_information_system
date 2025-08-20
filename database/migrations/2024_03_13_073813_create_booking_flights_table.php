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
        Schema::create('booking_flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->references('id')->on('bookings');
            $table->string('gds_no', 100)->nullable();
            $table->string('airline_locator', 100)->nullable();
            $table->string('ticket_no', 100)->nullable();
            $table->string('flight_number', 100)->nullable();
            //$table->foreignId('departure_airport_id')->references('id')->on('airports');
            $table->string('departure_airport', 100);
            $table->date('departure_date');
            $table->time('departure_time')->nullable();
            //$table->foreignId('arrival_airport_id')->references('id')->on('airports');
            $table->string('arrival_airport', 100);
            $table->date('arrival_date');
            $table->time('arrival_time')->nullable();
            //$table->foreignId('air_line_id')->references('id')->on('airlines');
            $table->string('air_line_name',100)->nullable();
            $table->string('booking_class', 100)->nullable();
            $table->string('number_of_baggage', 20)->nullable();

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
        Schema::dropIfExists('booking_flights');
    }
};
