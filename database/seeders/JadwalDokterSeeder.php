<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalDokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jadwal_dokters')->insert([
            [
                'id_dokter' => 1,
                'hari' => 'Senin',
                'tanggal' => '2024-07-22',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '12:00:00',
                'kuota' => 10,
            ],
            [
                'id_dokter' => 1,
                'hari' => 'Rabu',
                'tanggal' => '2024-07-24',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '17:00:00',
                'kuota' => 7,
            ],
            [
                'id_dokter' => 2,
                'hari' => 'Selasa',
                'tanggal' => '2024-07-23',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '14:00:00',
                'kuota' => 5,
            ],
        ]);
    }
}
