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
            $table->float('actual_net_cost', 15,2)->nullable()->after('net_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_transports', function (Blueprint $table) {
            $table->dropColumn('actual_net_cost');
        });
    }
};
