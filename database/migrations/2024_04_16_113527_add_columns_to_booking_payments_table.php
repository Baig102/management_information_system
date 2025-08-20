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
            $table->foreignId('other_charges_id')->after('booking_id')->nullable()->references('id')->on('booking_other_charges');//->onDelete('cascade');
            $table->text('comments')->nullable()->after('payment_on');
            //$table->softDeletes()->after('updated_at'); // This adds a deleted_at column for soft deletes
            //$table->foreignId('deleted_by')->after('deleted_at')->nullable()->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropColumn('other_charges_id');
            $table->dropColumn('comments');
            //$table->dropColumn('deleted_at');
            //$table->dropColumn('deleted_by');
        });
    }
};
