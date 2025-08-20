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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('invoice_prefix', 5);
            $table->integer('booking_number')->unsigned();
            $table->string('logo', 100)->nullable()->default('no-image.png');
            $table->string('email', 100);
            $table->string('secondary_email', 100);
            $table->string('phone', 20);
            $table->string('website', 150)->nullable();
            $table->string('address', 255)->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->integer('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
