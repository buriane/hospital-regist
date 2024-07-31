@extends('layouts.app')

@section('title', 'Jadwal Praktik Dokter')

@section('content')
<div class="bg-light-blue p-4 sm:p-8 rounded-2xl mt-4 sm:mt-8 mx-auto mb-8 sm:mb-16 text-center max-w-[90%] sm:max-w-screen-sm md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl">
    <h1 class="text-2xl sm:text-2xl md:text-3xl font-bold mb-4 sm:mb-8 mt-4 sm:mt-8"><strong>Jadwal Praktik Dokter</strong></h1>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px] sm:min-w-full">
            <thead>
                <tr class="bg-transparent text-dark-gray">
                    <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap">&nbsp;</th>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <th class="border border-gray-300 p-2 sm:p-3 w-28 whitespace-nowrap">{{ $hari }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($polikliniks as $poliklinik)
                    <tr class="bg-blue text-light-gray">
                        <td colspan="8" class="p-2 sm:p-3 font-bold text-left text-light-gray whitespace-nowrap bg-blue border-b-2 border-light-blue">{{ $poliklinik->nama_poliklinik }}</td>
                    </tr>
                    @foreach($poliklinik->dokter as $dokter)
                        <tr>
                            <td class="border border-gray-300 p-2 sm:p-3 pl-10 text-left whitespace-nowrap">{{ $dokter->nama_dokter }}</td>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <td class="border border-gray-300 p-2 sm:p-3 text-center whitespace-nowrap">
                                    @php
                                        $tanggalHariIni = $startOfWeek->copy()->addDays(['Senin' => 0, 'Selasa' => 1, 'Rabu' => 2, 'Kamis' => 3, 'Jumat' => 4, 'Sabtu' => 5, 'Minggu' => 6][$hari]);
                                        $jadwalKhusus = $dokter->jadwalKhususDokters->where('tanggal', $tanggalHariIni->toDateString());
                                        $jadwalRegular = $dokter->jadwalDokters->where('hari', $hari);
                                        $sedangCuti = $dokter->cutiDokters->contains(function ($cuti) use ($tanggalHariIni) {
                                            return $tanggalHariIni->between($cuti->tanggal_mulai, $cuti->tanggal_selesai);
                                        });
                                    @endphp
                                    @if($sedangCuti)
                                        <span class="ml-2 px-2 py-1 text-sm font-semibold bg-red-500 text-white rounded-full animate-pulse tracking-wider">Cuti</span>
                                    @elseif($jadwalKhusus->isNotEmpty())
                                        @foreach($jadwalKhusus->sortBy('jam_mulai') as $khusus)
                                            {{ date('H:i', strtotime($khusus->jam_mulai)) }} - {{ date('H:i', strtotime($khusus->jam_selesai)) }}<br>
                                        @endforeach
                                    @elseif($jadwalRegular->isNotEmpty())
                                        @foreach($jadwalRegular->sortBy('jam_mulai')->unique(function ($item) {
                                            return $item->jam_mulai . $item->jam_selesai;
                                        }) as $item)
                                            {{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}<br>
                                        @endforeach
                                    @else
                                        
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection