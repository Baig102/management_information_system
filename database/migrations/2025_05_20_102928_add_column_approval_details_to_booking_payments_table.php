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
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('receipt_voucher');
            $table->foreignId('approved_by')->nullable()->after('is_approved')->references('id')->on('users');
            $table->date('approved_on')->nullable()->after('approved_by');
            $table->string('approval_comments')->nullable()->after('approved_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn('is_approved');
            $table->dropColumn('approved_by');
            $table->dropColumn('approved_on');
            $table->dropColumn('approval_comments');
        });
    }
};
