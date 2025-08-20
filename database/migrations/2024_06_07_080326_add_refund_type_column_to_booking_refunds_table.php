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
        Schema::table('booking_refunds', function (Blueprint $table) {
            $table->string('refund_type', 25)->after('booking_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_refunds', function (Blueprint $table) {
            $table->dropColumn('refund_type');
        });
    }
};
