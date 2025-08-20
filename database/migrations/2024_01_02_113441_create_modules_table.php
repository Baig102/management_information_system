<?php

use App\Models\Module;
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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('module_link', 255);
            $table->string('icon', 255);
            $table->boolean('is_active')->default(false);
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();
        });
        /* Module::create(
            [
                'name' => 'Human Resource Management System',
                'module_link' => 'hrm',
                'icon' => '<span class="avatar-title bg-warning-subtle rounded fs-3 avatar-title bg-primary-subtle rounded fs-3 avatar-lg img-thumbnail rounded-circle flex-shrink-0"> <i class="bx bx-user-circle text-warning"></i> </span>',
                'is_active' => true,
                'status' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'Accounts Management System',
                'module_link' => 'ams',
                'icon' => '<span class="avatar-title bg-primary-subtle rounded fs-3 avatar-title bg-primary-subtle rounded fs-3 avatar-lg img-thumbnail rounded-circle flex-shrink-0"> <i class="bx bx-wallet text-primary"></i> </span>',
                'is_active' => true,
                'status' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'Customer Relation Management System',
                'module_link' => 'crm',
                'icon' => '<span class="avatar-title bg-warning-subtle rounded fs-3 avatar-title bg-primary-subtle rounded fs-3 avatar-lg img-thumbnail rounded-circle flex-shrink-0"> <i class="bx bx-user-circle text-warning"></i> </span>',
                'is_active' => true,
                'status' => '1',
                'created_at' => now()
            ]
        ); */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
