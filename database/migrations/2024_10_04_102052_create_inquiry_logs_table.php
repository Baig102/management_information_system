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
        Schema::create('inquiry_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->references('id')->on('inquiries');
            $table->string('action', 100)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_logs');
    }
};
