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
                'nama_poliklinik' => 'POLIKLINIK ANAK',
            ],
            [
                'nama_poliklinik' => 'POLIKLINIK MATA',
            ],
            [
                'nama_poliklinik' => 'POLIKLINIK THT',
            ],
            [
                'nama_poliklinik' => 'POLIKLINIK SARAF',
            ],
            [
                'nama_poliklinik' => 'POLIKLINIK JIWA',
            ],
            [
                'nama_poliklinik' => 'POLIKLINIK KEBIDANAN (OBSTETRI)',
            ],
            [
                'nama_poliklinik' => 'POLIKLINIK PARU',
            ],
            [
                'nama_poliklinik' => 'POLIKLINIK KULIT DAN KELAMIN',
            ],
            [
                'nama_poliklinik' => 'POLIKLINIK INTERNIS',
            ],
        ]);
    }
}
