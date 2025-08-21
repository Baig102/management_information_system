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
        Schema::table('booking_pnrs', function (Blueprint $table) {
            $table->decimal('supplier_discount', 15, 2)->nullable()->default(0.00)->after('actual_net_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_pnrs', function (Blueprint $table) {
            $table->dropColumn('supplier_discount');
        });
    }
};
