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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->unsigned()->nullable()->default(0)->references('id')->on('menus');
            $table->foreignId('module_id')->unsigned()->references('id')->on('modules');
            $table->string('module_link', 100);
            $table->string('name', 100);
            $table->string('description', 100)->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('badge', 100)->nullable();
            $table->string('route_name', 100)->comment('url');
            $table->integer('order_by')->unsigned()->default(0);
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
        Schema::dropIfExists('menus');
    }
};
