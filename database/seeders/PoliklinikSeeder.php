<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliklinikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('polikliniks')->insert([
            [
                'nama_poliklinik' => 'Gigi dan Mulut',
            ],
            [
                'nama_poliklinik' => 'Kandungan',
            ],
        ]);
    }
}
