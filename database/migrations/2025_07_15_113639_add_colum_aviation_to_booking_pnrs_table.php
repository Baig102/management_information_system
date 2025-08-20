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
            $table->string('aviation_fee_supplier', 150)->nullable()->after('actual_net_by');
            $table->decimal('aviation_fee', 15, 2)->nullable()->default(0.00)->after('aviation_fee_supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_pnrs', function (Blueprint $table) {
            $table->dropColumn('aviation_fee_supplier');
            $table->dropColumn('aviation_fee');
        });
    }
};
