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
        Schema::create('inquiry_assigment_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiries_assignment_id')->references('id')->on('inquiry_assigments');
            $table->string('inquiry_status', 50)->nullable();
            $table->string('comments', 255)->nullable();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->timestamps();
            $table->smallInteger('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_assigment_actions');
    }
};
