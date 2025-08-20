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
        Schema::table('booking_pricings', function (Blueprint $table) {
            $table->decimal('aviation_fee_total', 15, 2)->nullable()->default(0.00)->after('actual_net_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_pricings', function (Blueprint $table) {
            $table->dropColumn('aviation_fee_total');
        });
    }
};
