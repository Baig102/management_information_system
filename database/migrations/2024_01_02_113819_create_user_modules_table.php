<?php

use App\Models\UserModules;
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
        Schema::create('user_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->comment('Users Table ID');
            $table->foreignId('module_id')->references('id')->on('modules')->comment('Modules Table ID');
            $table->integer('user_module_level')->unsigned();
            $table->integer('access_type')->unsigned()->default(1)->comment('1->Temporary, 2->Permanent');
            $table->date('access_to_date')->nullable()->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->foreignId('created_by')->references('id')->on('users')->comment('Users Table ID');
            $table->foreignId('update_by')->nullable()->references('id')->on('users')->comment('Users Table ID');
            $table->timestamps();
            $table->boolean('is_active')->default(false);
            $table->integer('status')->unsigned()->default(1);
        });

        /* UserModules::create(
            [
                'user_id'           => '1',
                'module_id'         => '1',
                'user_module_level' => '1',
                'access_type'       => '2',
                'access_to_date'    => now(),
                'created_by'        => '1',
                'created_at'        => now(),
                'is_active'         => true,
                'status'            => '1',
            ],
            [
                'user_id'           => '1',
                'module_id'         => '2',
                'user_module_level' => '1',
                'access_type'       => '2',
                'access_to_date'    => now(),
                'created_by'        => '1',
                'created_at'        => now(),
                'is_active'         => true,
                'status'            => '1',
            ],
            [
                'user_id'           => '1',
                'module_id'         => '3',
                'user_module_level' => '1',
                'access_type'       => '2',
                'access_to_date'    => now(),
                'created_by'        => '1',
                'created_at'        => now(),
                'is_active'         => true,
                'status'            => '1',
            ],

        ); */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_modules');
    }
};
