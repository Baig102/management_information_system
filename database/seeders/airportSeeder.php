<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class airportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('airports')->insert([

            [
                'name' => 'A Coruna Airport',
                'iata' => 'LCG',
                'icao' => 'LECO',
                'city' => 'A Coruna',
                'lat' => '43.302059',
                'lon' => '-8.37725',
                'country' => 'Spain',
                'alt' => 326,
                'size' => 31900,
                'is_active' => 1,
                'status' => 1
            ],

            [
                'name' => 'Aachen Merzbruck Airport',
                'iata' => 'AAH',
                'icao' => 'EDKA',
                'city' => 'Aachen',
                'lat' => '50.823051',
                'lon' => '6.186111',
                'country' => 'Germany',
                'alt' => 623,
                'size' => 10860,
                'is_active' => 1,
                'status' => 1
            ],

            [
                'name' => 'Aalborg Airport',
                'iata' => 'AAL',
                'icao' => 'EKYT',
                'city' => 'Aalborg',
                'lat' => '57.092781',
                'lon' => '9.849164',
                'country' => 'Denmark',
                'alt' => 10,
                'size' => 72936,
                'is_active' => 1,
                'status' => 1
            ],

            [
                'name' => 'Aarhus Airport',
                'iata' => 'AAR',
                'icao' => 'EKAH',
                'city' => 'Aarhus',
                'lat' => '56.300011',
                'lon' => '10.619',
                'country' => 'Denmark',
                'alt' => 82,
                'size' => 31164,
                'is_active' => 1,
                'status' => 1
            ],

            [
                'name' => 'Aarhus Sea Airport',
                'iata' => 'QEA',
                'icao' => 'EKAC',
                'city' => 'Aarhus',
                'lat' => '56.151993',
                'lon' => '10.247725',
                'country' => 'Denmark',
                'alt' => 1,
                'size' => 6308,
                'is_active' => 1,
                'status' => 1
            ],

            [
                'name' => 'Aasiaat Airport',
                'iata' => 'JEG',
                'icao' => 'BGAA',
                'city' => 'Aasiaat',
                'lat' => '68.72184',
                'lon' => '-52.784698',
                'country' => 'Greenland',
                'alt' => 74,
                'size' => 2608,
                'is_active' => 1,
                'status' => 1
            ],

            [
                'name' => 'Abadan Airport',
                'iata' => 'ABD',
                'icao' => 'OIAA',
                'city' => 'Abadan',
                'lat' => '30.371111',
                'lon' => '48.228329',
                'country' => 'Iran',
                'alt' => 19,
                'size' => 10158,
                'is_active' => 1,
                'status' => 1
            ],

            [
                'name' => 'Abakan International Airport',
                'iata' => 'ABA',
                'icao' => 'UNAA',
                'city' => 'Abakan',
                'lat' => '53.740002',
                'lon' => '91.385002',
                'country' => 'Russia',
                'alt' => 831,
                'size' => 6780,
                'is_active' => 1,
                'status' => 1
            ],

            [
                'name' => 'Abbotsford International Airport',
                'iata' => 'YXX',
                'icao' => 'CYXX',
                'city' => 'Abbotsford',
                'lat' => '49.025269',
                'lon' => '-122.360001',
                'country' => 'Canada',
                'alt' => 195,
                'size' => 62929,
                'is_active' => 1,
                'status' => 1
            ],

            [
                'name' => 'Aberdeen International Airport',
                'iata' => 'ABZ',
                'icao' => 'EGPD',
                'city' => 'Aberdeen',
                'lat' => '57.201939',
                'lon' => '-2.19777',
                'country' => 'United Kingdom',
                'alt' => 215,
                'size' => 193535,
                'is_active' => 1,
                'status' => 1
            ],


        ]);
    }
}
