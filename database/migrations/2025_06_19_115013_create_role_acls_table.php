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
        Schema::create('role_acls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->references('id')->on('modules');
            $table->string('module_prefix', 10);
            $table->foreignId('role_id')->references('id')->on('employee_roles');
            $table->string('role_name', 100);
            $table->string('menu_name', 100)->nullable();
            $table->string('url_name', 100);
            $table->string('url', 255);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('role_acls');
    }
};
