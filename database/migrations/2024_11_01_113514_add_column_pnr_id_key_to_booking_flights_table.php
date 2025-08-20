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
            $table->foreignId('pnr_id')->nullable()->after('booking_id');
            $table->string('pnr_key', 50)->nullable()->after('pnr_id');
        });

        // Adding the foreign key constraint after the column has been created
        Schema::table('booking_flights', function (Blueprint $table) {
            $table->foreign('pnr_id')->references('id')->on('booking_pnrs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_flights', function (Blueprint $table) {
            $table->dropForeign(['pnr_id']); // Specify the column in an array
            $table->dropColumn('pnr_key');
        });
    }
};
