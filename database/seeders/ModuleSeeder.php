<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* DB::table('modules')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'password' => Hash::make('password'),
        ]); */
        DB::table('modules')->insert([
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
        ]);
    }
}
