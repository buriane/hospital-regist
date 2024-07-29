@extends('layouts.app')

@section('title', 'Info Kuota Dokter')

@section('content')
<div class="bg-light-blue p-4 sm:p-8 rounded-2xl mt-4 sm:mt-8 mx-auto mb-8 sm:mb-16 text-center max-w-[90%] sm:max-w-screen-sm md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl">
    <h1 class="text-2xl sm:text-2xl md:text-3xl font-bold mb-4 sm:mb-8 mt-4 sm:mt-8">
        <strong>Informasi Kuota Reservasi, {{ $dayName }} ({{ $tomorrow->format('d-m-Y') }})</strong>
    </h1>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px] sm:min-w-full">
            <thead>
                <tr class="bg-transparent text-dark-gray">
                    <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap">&nbsp;</th>
                    <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap w-28">Jam Praktik</th>
                    <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap w-28">Sisa Kuota</th>
                </tr>
            </thead>
            <tbody>
                @foreach($polikliniks as $poliklinik)
                    @php
                        $hasDoctorSchedules = $poliklinik->dokter->flatMap->effectiveSchedule->isNotEmpty();
                        $hasAvailableQuota = $poliklinik->dokter->flatMap->effectiveSchedule->where('kuota', '>', 0)->isNotEmpty();
                    @endphp
                    <tr>
                        <td colspan="3" class="p-2 sm:p-3 font-bold text-left text-light-gray whitespace-nowrap bg-blue border-b-2 border-light-blue">
                            {{ strtoupper($poliklinik->nama_poliklinik) }}
                            @if(!$hasDoctorSchedules)
                                <span class="ml-2 px-2 py-1 text-xs font-semibold bg-red-500 text-white rounded-full animate-pulse">
                                    Tidak ada jadwal dokter yang tersedia
                                </span>
                            @elseif(!$hasAvailableQuota)
                                <span class="ml-2 px-2 py-1 text-xs font-semibold bg-yellow-500 text-white rounded-full animate-pulse">
                                    Kuota sudah penuh
                                </span>
                            @endif
                        </td>
                    </tr>
                    @if($hasDoctorSchedules)
                        @foreach($poliklinik->dokter as $dokter)
                            @foreach($dokter->effectiveSchedule as $jadwal)
                                <tr>
                                    <td class="border border-gray-300 p-2 sm:p-3 pl-10 text-left whitespace-nowrap">{{ $dokter->nama_dokter }}</td>
                                    <td class="border border-gray-300 p-2 sm:p-3 text-center whitespace-nowrap">
                                        {{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}
                                    </td>
                                    <td class="border border-gray-300 p-2 sm:p-3 text-center font-bold whitespace-nowrap">
                                        @if($jadwal->kuota > 0)
                                            <strong>{{ $jadwal->kuota }}</strong>
                                        @else
                                            <span class="text-red-500 font-bold"><strong>Kuota Penuh</strong></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection