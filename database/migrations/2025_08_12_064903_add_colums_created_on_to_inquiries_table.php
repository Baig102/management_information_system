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
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dateTime('created_on')->nullable()->after('created_at')->comment('The date and time when the data was inserted');
            $table->string('facebook_account', 100)->nullable()->after('inquiry_page_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('facebook_account');
        });
    }
};
