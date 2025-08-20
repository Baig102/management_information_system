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
            $table->boolean('is_ownership_changed')->nullable()->default(false)->after('booking_type');
            $table->foreignId('ownership_changed_by')->nullable()->after('is_ownership_changed')->references('id')->on('users');
            $table->date('ownership_changed_on')->nullable()->after('ownership_changed_by');
            $table->string('ownership_change_reason', 255)->nullable()->after('ownership_changed_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('is_ownership_changed');
            $table->dropForeign(['ownership_changed_by']);
            $table->dropColumn('ownership_changed_by');
            $table->dropColumn('ownership_changed_on');
            $table->dropColumn('ownership_change_reason');
        });
    }
};
