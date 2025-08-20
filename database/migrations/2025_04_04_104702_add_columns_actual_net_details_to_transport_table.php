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
            $table->date('actual_net_on')->nullable()->after('updated_at');
            $table->foreignId('actual_net_by')->nullable()->after('actual_net_on')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_transports', function (Blueprint $table) {
            $table->dropForeign(['actual_net_by']);
            $table->dropColumn('actual_net_on');
            $table->dropColumn('actual_net_by');
        });
    }
};
