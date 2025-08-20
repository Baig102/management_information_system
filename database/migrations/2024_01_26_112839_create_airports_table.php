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
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('iata', 10);
            $table->string('icao', 10);
            $table->string('city', 100);
            $table->string('lat', 20);
            $table->string('lon', 20);
            $table->string('country', 100);
            $table->string('alt', 10);
            $table->string('size', 10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->integer('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};
