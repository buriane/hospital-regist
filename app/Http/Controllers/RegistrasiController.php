<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Registrasi;
use App\Models\Poliklinik;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\CutiDokter;
use Carbon\Carbon;

class RegistrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polikliniks = Poliklinik::all();
        $jadwal = JadwalDokter::with(['poliklinik', 'dokter'])->get();
    
        return view('regis.index', compact('polikliniks', 'jadwal'));
    }

    public function checkPatient(Request $request)
    {
        $patient = Pasien::where('nomor_rm', $request->nomor_rm)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->first();

        if ($patient) {
            return response()->json([
                'exists' => true,
                'id_pasien' => $patient->id_pasien,
                'nama_pasien' => $patient->nama_pasien,
                'tempat_lahir' => $patient->tempat_lahir,
                'jenis_kelamin' => $patient->jenis_kelamin,
                'alamat' => $patient->alamat,
                'nomor_telepon' => $patient->nomor_telepon,
                'email' => $patient->email,
                'nomor_kartu' => $patient->nomor_kartu,
                'tanggal_lahir' => $patient->tanggal_lahir,
            ]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    private function isCodeExists($code)
    {
        return Registrasi::where('kode_booking', $code)->exists();
    }

    public function generateCustomCode()
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
    
        do {
            $firstTwoLetters = $letters[random_int(0, strlen($letters) - 1)] . $letters[random_int(0, strlen($letters) - 1)];
            $threeNumbers = '';
            for ($i = 0; $i < 3; $i++) {
                $threeNumbers .= $numbers[random_int(0, strlen($numbers) - 1)];
            }
            $lastLetter = $letters[random_int(0, strlen($letters) - 1)];
    
            $customCode = $firstTwoLetters . $threeNumbers . $lastLetter;
    
        } while ($this->isCodeExists($customCode));
    
        return $customCode;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pasien' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'required|string',
            'email' => 'required|email',
            'nomor_kartu' => 'nullable|string',
            'tanggal_kunjungan' => 'required|date',
            'poliklinik' => 'required',
            'dokter' => 'required',
        ]);
        $kode = $this->generateCustomCode();
        if ($request->id_pasien === null) {
            $pasien = new Pasien();
            $pasien->nomor_rm = null;
            $pasien->nama_pasien = $validatedData['nama_pasien'];
            $pasien->tempat_lahir = $validatedData['tempat_lahir'];
            $pasien->tanggal_lahir = $validatedData['tanggal_lahir'];
            $pasien->jenis_kelamin = $validatedData['jenis_kelamin'];
            $pasien->alamat = $validatedData['alamat'];
            $pasien->nomor_telepon = $validatedData['nomor_telepon'];
            $pasien->email = $validatedData['email'];
            $pasien->nomor_kartu = $validatedData['nomor_kartu'];
            $pasien->save();

            $registrasi = new Registrasi();
            $registrasi->id_pasien = $pasien->id_pasien;
            $registrasi->tanggal_kunjungan = $validatedData['tanggal_kunjungan'];
            $registrasi->id_poliklinik = $validatedData['poliklinik'];
            $registrasi->id_dokter = $validatedData['dokter'];
            $registrasi->kode_booking = $kode;
            $registrasi->save();
        } else {
            $registrasi = new Registrasi();
            $registrasi->id_pasien = $request->id_pasien;
            $registrasi->tanggal_kunjungan = $validatedData['tanggal_kunjungan'];
            $registrasi->id_poliklinik = $validatedData['poliklinik'];
            $registrasi->id_dokter = $validatedData['dokter'];
            $registrasi->kode_booking = $kode;
            $registrasi->save();
        }

        $jadwal = JadwalDokter::where("id_dokter", $registrasi->id_dokter)->where("tanggal", $registrasi->tanggal_kunjungan)->first();
        $jadwal->kuota = $jadwal->kuota - 1;
        $jadwal->save();

        $besok = Carbon::tomorrow()->format('d-m-Y');
        return view('regis.index', compact('kode', 'besok'));
    }

    public function jadwal($tgl)
    {
        $jadwal = JadwalDokter::with(['poliklinik', 'dokter'])
            ->where('tanggal', $tgl)
            ->where('kuota', '>', 0)
            ->get();
    
        foreach ($jadwal as $value) {
            $value->jam_mulai = date('H:i', strtotime($value->jam_mulai));
            $value->jam_selesai = date('H:i', strtotime($value->jam_selesai));
        }
    
        return response()->json(['jadwal' => $jadwal]);
    }

    function download_pdf($kode, $tanggal)
    {
        $mpdf = new \Mpdf\Mpdf();
        $html = "
        <h1 style='text-align: center;'>Bukti Pendaftaran RSU St. Elisabeth Purwokerto</h1>
        <p><strong>Kode Booking:</strong> {$kode}</p>
        <p><strong>Tanggal Kunjungan:</strong> {$tanggal}</p>
        ";
        
        $mpdf->WriteHTML($html);
        
        return $mpdf->Output('bukti-pendaftaran-rsu-elisabeth-purwokerto.pdf', 'D');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
