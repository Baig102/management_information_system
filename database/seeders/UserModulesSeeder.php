<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_modules')->insert([
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
            ]
        ]);
    }
}
