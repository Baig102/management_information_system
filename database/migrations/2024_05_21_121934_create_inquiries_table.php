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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies');
            $table->string('company_name', 100);
            $table->string('source', 50)->nullable()->default('Web Site');
            $table->bigInteger('campaign_id')->nullable();
            $table->bigInteger('campaign_lead_id')->nullable();
            $table->bigInteger('campaign_ad_id')->nullable();
            $table->json('campaign_insights')->nullable();
            $table->string('inquiry_type', 50)->nullable();
            $table->string('flight_type', 20)->nullable();
            $table->string('airline', 150)->nullable();
            $table->string('cabin_class', 100)->nullable()->comment('In case of hotel, it acts as Room');
            $table->string('departure_airport', 225)->nullable();
            $table->date('departure_date')->nullable()->comment('In case of hotel, it acts as Check In Date');
            $table->string('arrival_airport', 225)->nullable()->comment('In case of hotel, it acts as destination');
            $table->date('arrival_date')->nullable()->comment('In case of hotel, it acts as Check Out Date');
            $table->integer('nights_in_makkah')->unsigned()->nullable();
            $table->integer('nights_in_madina')->unsigned()->nullable();
            $table->integer('no_of_adult_travelers')->unsigned()->nullable();
            $table->integer('no_of_child_travelers')->unsigned()->nullable();
            $table->integer('no_of_infant_travelers')->unsigned()->nullable();
            $table->string('preferred_hotel_type', 50)->nullable();
            $table->string('lead_passenger_name', 150)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('contact_number', 150)->nullable();
            $table->string('best_time_to_contact', 150)->nullable();
            $table->string('transfer_required', 10)->nullable();
            $table->string('inquiry_page_url', 225)->nullable();
            $table->smallInteger('inquiry_assignment_status')->nullable();
            $table->dateTime('inquiry_assignment_on')->nullable();
            $table->bigInteger('inquiry_assigned_to')->nullable();
            $table->timestamps();
            $table->smallInteger('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
