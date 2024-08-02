<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Registrasi;
use App\Models\Poliklinik;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\CutiDokter;
use App\Models\JadwalKhususDokter;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\DNS2D;

class RegistrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polikliniks = Poliklinik::all();
        $today = Carbon::now();
        $tomorrow = Carbon::tomorrow();
        
        // Fetch special schedules with quota > 0
        $jadwalKhusus = JadwalKhususDokter::with(['dokter'])
            ->whereDate('tanggal', '>=', $today)
            ->where('kuota', '>', 0)
            ->whereDoesntHave('dokter.cutiDokter', function ($query) use ($today, $tomorrow) {
                $query->where('tanggal_mulai', '<=', $tomorrow)
                        ->where('tanggal_selesai', '>=', $today);
            })
            ->get();

        // Get all special schedules (including zero quota) for blocking regular schedules of the same doctor
        $allJadwalKhusus = JadwalKhususDokter::whereDate('tanggal', '>=', $today)->get();
        $specialSchedules = $allJadwalKhusus->groupBy(function ($item) {
            return $item->tanggal . '-' . $item->id_dokter;
        })->map(function ($group) {
            return $group->first();
        });

        // Fetch regular schedules
        $jadwal = JadwalDokter::with(['poliklinik', 'dokter'])
            ->where('kuota', '>', 0)
            ->whereDoesntHave('dokter.cutiDokter', function ($query) use ($today, $tomorrow) {
                $query->where('tanggal_mulai', '<=', $tomorrow)
                        ->where('tanggal_selesai', '>=', $today);
            })
            ->get();
            
        return view('regis.index', compact('polikliniks', 'jadwal', 'jadwalKhusus', 'specialSchedules'));
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

        $tanggalKunjungan = Carbon::parse($validatedData['tanggal_kunjungan']);
        $hariKunjungan = $tanggalKunjungan->locale('id')->dayName;

        $jamMulai = $request->jam_mulai;
        $jamSelesai = $request->jam_selesai;

        $id_jadwal =null;
        $id_jadwal_khusus = null;

        $jadwalKhusus = JadwalKhususDokter::where('id_dokter', $validatedData['dokter'])
            ->whereDate('tanggal', $tanggalKunjungan)
            ->where('jam_mulai', $jamMulai)
            ->where('jam_selesai', $jamSelesai)
            ->first();

        if ($jadwalKhusus) {
            $jadwalKhusus->kuota = $jadwalKhusus->kuota - 1;
            $jadwalKhusus->save();
            $no_urut = Registrasi::where('id_jadwal_khusus_dokter', $jadwalKhusus->id_jadwal_khusus_dokter)->count()+1;
            $id_jadwal_khusus = $jadwalKhusus->id_jadwal_khusus_dokter;

        } else {
            $jadwal = JadwalDokter::where('id_dokter', $validatedData['dokter'])
                ->where('hari', $hariKunjungan)
                ->where('jam_mulai', $jamMulai)
                ->where('jam_selesai', $jamSelesai)
                ->first();

            if ($jadwal) {
                $jadwal->kuota = $jadwal->kuota - 1;
                $jadwal->save();
                $no_urut = Registrasi::where('id_jadwal_dokter', $jadwal->id_jadwal_dokter)->count()+1;
                $id_jadwal = $jadwal->id_jadwal_dokter;
            }
        }
        
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
            $registrasi->jam_mulai = $request->jam_mulai;
            $registrasi->jam_selesai = $request->jam_selesai;
            $registrasi->nomor_urut = $no_urut;
            $registrasi->id_jadwal_dokter = $id_jadwal;
            $registrasi->id_jadwal_khusus_dokter = $id_jadwal_khusus;
            $registrasi->save();
        } else {
            $registrasi = new Registrasi();
            $registrasi->id_pasien = $request->id_pasien;
            $registrasi->tanggal_kunjungan = $validatedData['tanggal_kunjungan'];
            $registrasi->id_poliklinik = $validatedData['poliklinik'];
            $registrasi->id_dokter = $validatedData['dokter'];
            $registrasi->kode_booking = $kode;
            $registrasi->jam_mulai = $request->jam_mulai;
            $registrasi->jam_selesai = $request->jam_selesai;
            $registrasi->nomor_urut = $no_urut;
            $registrasi->id_jadwal_dokter = $id_jadwal;
            $registrasi->id_jadwal_khusus_dokter = $id_jadwal_khusus;
            $registrasi->save();
        }
    
        $besok = Carbon::parse($request->tanggal_kunjungan)->format('d-m-Y');
        return view('regis.index', compact('kode', 'besok', 'no_urut'));
    }

    public function jadwal($tgl)
    {
        $hariKunjungan = Carbon::parse($tgl)->locale('id')->dayName;
    
        $jadwalKhusus = JadwalKhususDokter::with(['dokter'])
            ->whereDate('tanggal', $tgl)
            ->where('kuota', '>', 0)
            ->whereDoesntHave('dokter.cutiDokter', function ($query) use ($tgl) {
                $query->where('tanggal_mulai', '<=', $tgl)
                        ->where('tanggal_selesai', '>=', $tgl);
            })
            ->get();
    
        if ($jadwalKhusus->isEmpty()) {
            $jadwal = JadwalDokter::with(['poliklinik', 'dokter'])
                ->where('hari', $hariKunjungan)
                ->where('kuota', '>', 0)
                ->whereDoesntHave('dokter.cutiDokter', function ($query) use ($tgl) {
                    $query->where('tanggal_mulai', '<=', $tgl)
                            ->where('tanggal_selesai', '>=', $tgl);
                })
                ->get();
        } else {
            $jadwal = $jadwalKhusus;
        }
    
        foreach ($jadwal as $value) {
            $value->jam_mulai = date('H:i', strtotime($value->jam_mulai));
            $value->jam_selesai = date('H:i', strtotime($value->jam_selesai));
        }
    
        return response()->json(['jadwal' => $jadwal]);
    }

    public function download_pdf($kode, $tanggal)
    {
        $registrasi = Registrasi::with(['pasien', 'poliklinik', 'dokter'])
            ->where('kode_booking', $kode)
            ->first();

        if (!$registrasi) {
            abort(404, 'Registration not found');
        }

        $tanggalKunjungan = Carbon::parse($registrasi->tanggal_kunjungan);
        $hariKunjungan = $tanggalKunjungan->locale('id')->dayName;

        // Cek apakah ada jadwal khusus
        $jadwalKhusus = JadwalKhususDokter::where('id_dokter', $registrasi->id_dokter)
            ->whereDate('tanggal', $tanggalKunjungan)
            ->first();

        if ($jadwalKhusus) {
            $jadwal = $jadwalKhusus;
        } else {
            $jadwal = JadwalDokter::where('id_dokter', $registrasi->id_dokter)
                ->where('hari', $hariKunjungan)
                ->first();
        }

        $jamMulai = $registrasi->jam_mulai;
        $jamSelesai = $registrasi->jam_selesai;
        $no_urut = $registrasi->nomor_urut;

        $logoPath = public_path('logo.png');
        $logoData = base64_encode(file_get_contents($logoPath));

        $dns2d = new DNS2D();
        $qrcode = $dns2d->getBarcodePNG($kode, 'QRCODE', 5, 5);

        $html = view('pdf.bukti-pendaftaran', compact('registrasi', 'kode', 'tanggal', 'jadwal', 'logoData', 'qrcode', 'jamMulai', 'jamSelesai','no_urut'))->render();

        $pdf = PDF::loadHtml($html);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('bukti-pendaftaran-rsu-elisabeth-purwokerto.pdf');
    }

    public function emailCheck($email){
        $email_check = Pasien::where('email', $email)->first();
        if($email_check){
            return response()->json(['data' => true]);
        }else{
            return response()->json(['data' => false]);
        }
    }

    public function noKartuCheck($noKartu){
        $no_kartu_check = Pasien::where('nomor_kartu', $noKartu)->first();
        if($no_kartu_check){
            return response()->json(['data' => true]);
        } else{
            return response()->json(['data' => false]);
        }
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
