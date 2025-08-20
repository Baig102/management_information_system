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
        Schema::table('passengers', function (Blueprint $table) {
            // $table->foreignId('pnr_id')->references('id')->on('booking_pnrs')->after('booking_id')->nullable();
            // $table->string('pnr_key', 50)->after('pnr_id')->nullable();

            $table->foreignId('pnr_id')->nullable()->after('booking_id');
            $table->string('pnr_key', 50)->nullable()->after('pnr_id');
        });

        // Adding the foreign key constraint after the column has been created
        Schema::table('passengers', function (Blueprint $table) {
            $table->foreign('pnr_id')->references('id')->on('booking_pnrs');//->onDelete('set null'); // or onDelete('cascade') if you want to remove passengers when a booking is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropForeign(['pnr_id']); // Specify the column in an array
            $table->dropColumn('pnr_key');
        });
    }
};
