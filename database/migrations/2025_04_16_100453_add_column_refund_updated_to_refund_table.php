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
            $table->decimal('airline_penalty', 15, 2)->nullable()->default(0.00)->after('service_charges');
            $table->decimal('refund_charges', 15, 2)->nullable()->default(0.00)->after('airline_penalty');
            $table->decimal('supplier_fee', 15, 2)->nullable()->default(0.00)->after('refund_charges');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_refunds', function (Blueprint $table) {
            $table->dropColumn('airline_penalty');
            $table->dropColumn('refund_charges');
            $table->dropColumn('supplier_fee');
        });
    }
};
