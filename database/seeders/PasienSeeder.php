<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pasiens')->insert([
            [
                'nomor_rm' => '123456',
                'nama_pasien' => 'Jane Smith',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2024-08-20',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Pemuda No. 456, Surabaya',
                'nomor_telepon' => '087654321098',
                'email' => 'jane.smith@example.com',
                'nomor_kartu' => str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT),
            ],
            [
                'nomor_rm' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'nama_pasien' => 'Budi Santoso',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2024-11-30',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Asia Afrika No. 789, Bandung',
                'nomor_telepon' => '089876543210',
                'email' => 'budi.santoso@example.com',
                'nomor_kartu' => '',
            ],
        ]);
    }
}
