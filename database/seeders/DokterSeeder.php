<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dokter;
use Illuminate\Support\Facades\DB;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dokters')->insert([
            [
                'id_poliklinik' => 1,
                'nama_dokter' => 'Roy Chan',
            ],
            [
                'id_poliklinik' => 1,
                'nama_dokter' => 'Ghaza Alif',
            ],
            [
                'id_poliklinik' => 2,
                'nama_dokter' => 'Jehian Kurniawan',
            ],
        ]);
    }
}
