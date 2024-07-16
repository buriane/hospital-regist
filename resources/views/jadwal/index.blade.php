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
                    <th class="border border-gray-300 p-2 sm:p-3 w-28 whitespace-nowrap">Senin</th>
                    <th class="border border-gray-300 p-2 sm:p-3 w-28 whitespace-nowrap">Selasa</th>
                    <th class="border border-gray-300 p-2 sm:p-3 w-28 whitespace-nowrap">Rabu</th>
                    <th class="border border-gray-300 p-2 sm:p-3 w-28 whitespace-nowrap">Kamis</th>
                    <th class="border border-gray-300 p-2 sm:p-3 w-28 whitespace-nowrap">Jumat</th>
                    <th class="border border-gray-300 p-2 sm:p-3 w-28 whitespace-nowrap">Sabtu</th>
                    <th class="border border-gray-300 p-2 sm:p-3 w-28 whitespace-nowrap">Minggu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($polikliniks as $poliklinik)
                    <tr class="bg-blue text-light-gray">
                        <td colspan="8" class="p-2 sm:p-3 font-bold text-left whitespace-nowrap">{{ $poliklinik->nama_poliklinik }}</td>
                    </tr>
                    @foreach($poliklinik->dokter as $dokter)
                        <tr>
                            <td class="border border-gray-300 p-2 sm:p-3 pl-10 text-left whitespace-nowrap">{{ $dokter->nama_dokter }}</td>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <td class="border border-gray-300 p-2 sm:p-3 text-center whitespace-nowrap">
                                    @php
                                        $jadwal = $dokter->jadwalDokters->where('hari', $hari);
                                    @endphp
                                    @if($jadwal->isNotEmpty())
                                        @foreach($jadwal as $item)
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