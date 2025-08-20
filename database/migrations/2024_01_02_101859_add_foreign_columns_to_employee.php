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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('department_id')->after('designation')->nullable()->references('id')->on('departments');
            $table->foreignId('team_lead_id')->after('official_phone')->nullable()->references('id')->on('users');
            $table->foreignId('created_by')->after('is_active')->nullable()->references('id')->on('users');
            $table->foreignId('updated_by')->after('created_at')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('team_lead');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
};
