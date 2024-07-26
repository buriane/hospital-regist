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
                'nama_dokter' => 'dr. Florence Alexandra, Sp. A',
            ],
            [
                'id_poliklinik' => 1,
                'nama_dokter' => 'dr. Ariadne TH, Sp. A, Subsp. Neo (K)',
            ],
            [
                'id_poliklinik' => 2,
                'nama_dokter' => 'dr. Dyah Ratnaningsih AW, Sp. M',
            ],
            [
                'id_poliklinik' => 2,
                'nama_dokter' => 'dr. Benedicta Wayan SW, Sp. M',
            ],
            [
                'id_poliklinik' => 3,
                'nama_dokter' => 'dr. Ratnasari, Sp. THT-KL',
            ],
            [
                'id_poliklinik' => 4,
                'nama_dokter' => 'dr. William Prasetiyo, Sp. N, FIN, AIFU-K',
            ],
            [
                'id_poliklinik' => 5,
                'nama_dokter' => 'dr. Nonny Putri Intansari, Sp. KJ',
            ],
            [
                'id_poliklinik' => 5,
                'nama_dokter' => 'dr. Dian Ekawati Setiawan, Sp. KJ',
            ],
            [
                'id_poliklinik' => 6,
                'nama_dokter' => 'dr. Yelly Yuliati, Sp. OG',
            ],
            [
                'id_poliklinik' => 6,
                'nama_dokter' => 'dr. Amin Nurakhim, Sp. OG',
            ],
            [
                'id_poliklinik' => 6,
                'nama_dokter' => 'dr. Marta Isyana D., Sp. OG',
            ],
            [
                'id_poliklinik' => 6,
                'nama_dokter' => 'dr. Yosiana Wijaya, Sp. OG',
            ],
            [
                'id_poliklinik' => 7,
                'nama_dokter' => 'dr. Inge Cahya Ramadhani, Sp. P',
            ],
            [
                'id_poliklinik' => 8,
                'nama_dokter' => 'dr. Thianti Sylviningrum, Sp. DVE',
            ],
            [
                'id_poliklinik' => 8,
                'nama_dokter' => 'dr. Fresa Nathania R, M. Biomed, Sp. DVE',
            ],
            [
                'id_poliklinik' => 9,
                'nama_dokter' => 'dr. Joko R. Pambudi, Sp. PD-KR, FINASIM',
            ],
            [
                'id_poliklinik' => 9,
                'nama_dokter' => 'dr. Suyadi, Sp. PD',
            ],
            [
                'id_poliklinik' => 9,
                'nama_dokter' => 'dr. Andreas, Sp. PD, FINASIM, AIFO-K',
            ],
            [
                'id_poliklinik' => 9,
                'nama_dokter' => 'dr. Alvin Aditya S., M.Sc, Sp. PD',
            ],
            [
                'id_poliklinik' => 9,
                'nama_dokter' => 'dr. Yunanto Dwi N., Sp. PD, K-G.H',
            ],
        ]);
    }
}
