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
        Schema::table('booking_flights', function (Blueprint $table) {
            $table->boolean('is_refunded')->after('number_of_baggage')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_flights', function (Blueprint $table) {
            $table->dropColumn('is_refunded');
        });
    }
};
