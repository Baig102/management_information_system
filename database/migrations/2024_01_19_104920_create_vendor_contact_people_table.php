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
        Schema::create('vendor_contact_people', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('designation', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('phone')->unsigned()->nullable();
            $table->integer('whatsapp')->unsigned()->nullable();
            $table->boolean('is_active')->nullable()->default(false);
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->integer('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_contact_people');
    }
};
