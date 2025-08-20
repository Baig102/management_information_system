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
        Schema::create('type_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->references('id')->on('types');
            $table->integer('detail_number')->unsigned()->nullable();
            $table->string('name', 100);
            $table->string('details', 100)->nullable();
            $table->string('comments', 255)->nullable();
            $table->timestamps();
            $table->tinyInteger('status')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_details');
    }
};
