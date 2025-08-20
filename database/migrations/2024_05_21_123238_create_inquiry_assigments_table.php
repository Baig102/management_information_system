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
        Schema::create('inquiry_assigments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->references('id')->on('users');
            $table->foreignId('inquiry_id')->references('id')->on('inquiries');
            $table->string('comments', 255)->nullable();
            $table->string('recent_status', 50)->nullable();
            $table->dateTime('recent_status_on')->nullable();
            $table->foreignId('assigned_by')->references('id')->on('users');
            $table->dateTime('assigend_on')->nullable();
            $table->foreignId('canceled_by')->nullable()->references('id')->on('users');
            $table->dateTime('canceled_on')->nullable();
            $table->timestamps();
            $table->smallInteger('status')->nullable()->default(1)->comment('1-> Pending, 2-> Approved, 3-> Canceled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_assigments');
    }
};
