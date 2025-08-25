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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->references('id')->on('vendors');
            $table->foreignId('business_customer_id')->nullable()->references('id')->on('business_customers');
            $table->foreignId('created_by')->references('id')->on('users')->comment('Users Table ID');
            $table->string('account_head');
            $table->string('main_group');
            $table->string('sub_group_1');
            $table->string('sub_group_2');
            $table->string('detailed_group');
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
