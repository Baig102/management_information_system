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
        Schema::table('booking_transports', function (Blueprint $table) {
            $table->string('reference_no', 100)->nullable()->after('supplier')->comment('Reference number for the transport booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_transports', function (Blueprint $table) {
            $table->dropColumn('reference_no');
        });
    }
};
