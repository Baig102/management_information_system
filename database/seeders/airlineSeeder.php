<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class airlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('airlines')->insert([
            [
                'name'              => '21 Air',
                'code'              => '2I',
                'icao'              => 'CSB',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
            [
                'name'              => '25only Aviation',
                'code'              => '4Q',
                'icao'              => 'ONY',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
            [
                'name'              => '2Excel Aviation',
                'code'              => '',
                'icao'              => 'BRO',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
            [
                'name'              => '40-Mile Air',
                'code'              => 'Q5',
                'icao'              => 'MLA',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
            [
                'name'              => '748 Air Services',
                'code'              => 'FE',
                'icao'              => 'IHO',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
            [
                'name'              => '9 Air',
                'code'              => 'AQ',
                'icao'              => 'JYH',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
            [
                'name'              => 'Abakan Air',
                'code'              => 'S5',
                'icao'              => 'NKP',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
            [
                'name'              => 'ABS Jets',
                'code'              => '',
                'icao'              => 'ABP',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
            [
                'name'              => 'Abu Dhabi Aviation',
                'code'              => '',
                'icao'              => 'BAR',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
            [
                'name'              => 'ABX Air',
                'code'              => 'GB',
                'icao'              => 'ABX',
                'is_active'         => true,
                'created_at'        => now(),
                'status'            => '1',
            ],
        ]);
    }
}
