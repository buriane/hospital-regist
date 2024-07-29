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

class JadwalDokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
    
        $polikliniks = Poliklinik::with(['dokter' => function($query) {
            $query->has('jadwalDokters');
        }, 'dokter.jadwalDokters', 'dokter.cutiDokters', 'dokter.jadwalKhususDokters' => function($query) use ($startOfWeek, $endOfWeek) {
            $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
        }])->get();
    
        return view('jadwal.index', compact('polikliniks', 'startOfWeek', 'endOfWeek'));
    }

    public function indexinfo()
    {
        Carbon::setLocale('id');
        $today = now();
        $tomorrow = now()->addDay();
        $dayName = $tomorrow->translatedFormat('l');
        $dayOfWeek = $tomorrow->dayOfWeekIso;

        $polikliniks = Poliklinik::with(['dokter' => function($query) use ($tomorrow, $dayOfWeek) {
            $query->whereHas('jadwalKhususDokters', function($subQuery) use ($tomorrow) {
                $subQuery->where('tanggal', $tomorrow->toDateString());
            })->orWhereHas('jadwalDokters', function($subQuery) use ($dayOfWeek) {
                $subQuery->where('hari', $dayOfWeek);
            });
        }, 'dokter.jadwalKhususDokters' => function($query) use ($tomorrow) {
            $query->where('tanggal', $tomorrow->toDateString());
        }, 'dokter.jadwalDokters' => function($query) use ($dayOfWeek) {
            $query->where('hari', $dayOfWeek);
        }, 'dokter.cutiDokters'])->get();

        $polikliniks->each(function ($poliklinik) use ($today, $tomorrow) {
            $poliklinik->dokter = $poliklinik->dokter->filter(function ($dokter) use ($today, $tomorrow) {
                $onLeave = $dokter->cutiDokters->where('tanggal_mulai', '<=', $tomorrow)
                                                ->where('tanggal_selesai', '>=', $today)
                                                ->isNotEmpty();
                return !$onLeave;
            });

            $poliklinik->dokter->each(function ($dokter) use ($tomorrow) {
                $dokter->effectiveSchedule = $dokter->jadwalKhususDokters->where('tanggal', $tomorrow->toDateString())->isNotEmpty()
                    ? $dokter->jadwalKhususDokters->where('tanggal', $tomorrow->toDateString())
                    : $dokter->jadwalDokters;
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
