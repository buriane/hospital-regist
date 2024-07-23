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

class JadwalDokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polikliniks = Poliklinik::with(['dokter' => function($query) {
            $query->has('jadwalDokters');
        }, 'dokter.jadwalDokters', 'dokter.cutiDokters'])->get();
    
        return view('jadwal.index', compact('polikliniks'));
    }

    public function indexinfo()
    {
        Carbon::setLocale('id');
        $today = now();
        $tomorrow = now()->addDay();
        $dayName = $tomorrow->translatedFormat('l');
    
        $polikliniks = Poliklinik::with(['dokter' => function($query) use ($tomorrow) {
            $query->whereHas('jadwalDokters', function($subQuery) use ($tomorrow) {
                $subQuery->whereDate('tanggal', $tomorrow);
            });
        }, 'dokter.jadwalDokters' => function($query) use ($tomorrow) {
            $query->whereDate('tanggal', $tomorrow);
        }, 'dokter.cutiDokters'])->get();
    
        $polikliniks->each(function ($poliklinik) use ($today, $tomorrow) {
            $poliklinik->dokter = $poliklinik->dokter->filter(function ($dokter) use ($today, $tomorrow) {
                $onLeave = $dokter->cutiDokters->where('tanggal_mulai', '<=', $tomorrow)
                                                ->where('tanggal_selesai', '>=', $today)
                                                ->isNotEmpty();
                return !$onLeave;
            });
        });
    
        return view('info.index', compact('polikliniks', 'tomorrow', 'dayName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
