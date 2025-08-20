<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([

            [
                'name' => 'A Coruna Airport',
                'invoice_prefix' => 'LCG',
                'booking_number' => 'LECO',
                'logo' => 'A Coruna',
                'email' => '43.302059',
                'secondary_email' => '-8.37725',
                'phone' => 'Spain',
                'website' => 326,
                'address' => 31900,
                'created_by' => 1,
                'status' => 1
            ],
        ]);
    }
}
