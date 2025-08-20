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
        Schema::table('bookings', function (Blueprint $table) {
            $table->float('actual_net_cost', 15,2)->nullable()->after('margin');
            $table->float('actual_margin', 15,2)->nullable()->after('actual_net_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('actual_net_cost');
            $table->dropColumn('actual_margin');
        });
    }
};
