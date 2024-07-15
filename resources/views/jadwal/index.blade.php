@extends('layouts.app')

@section('title', 'Jadwal Praktik Dokter')

@section('content')
<div class="bg-light-blue p-4 sm:p-8 rounded-2xl mt-4 sm:mt-8 mx-auto mb-8 sm:mb-16 text-center max-w-[90%] sm:max-w-screen-sm md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl">
    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-4 sm:mb-8 mt-4 sm:mt-8"><strong>Jadwal Praktik Dokter</strong></h1>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px] sm:min-w-full">
            <thead>
                <tr class="bg-transparent text-dark-gray">
                    <th class="border border-gray-300 p-2">&nbsp;</th>
                    <th class="border border-gray-300 p-2 w-28">Senin</th>
                    <th class="border border-gray-300 p-2 w-28">Selasa</th>
                    <th class="border border-gray-300 p-2 w-28">Rabu</th>
                    <th class="border border-gray-300 p-2 w-28">Kamis</th>
                    <th class="border border-gray-300 p-2 w-28">Jum'at</th>
                    <th class="border border-gray-300 p-2 w-28">Sabtu</th>
                    <th class="border border-gray-300 p-2 w-28">Minggu</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-blue text-light-gray">
                    <td colspan="8" class="p-2 font-bold text-left">POLIKLINIK MATA</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 p-2 pl-10 text-left">dr. Andi</td>
                    <td class="border border-gray-300 text-center">08:00-12:00</td>
                    <td class="border border-gray-300 text-center">08:00-12:00</td>
                    <td class="border border-gray-300 text-center"></td>
                    <td class="border border-gray-300 text-center">08:00-12:00</td>
                    <td class="border border-gray-300 text-center">08:00-12:00</td>
                    <td class="border border-gray-300 text-center"></td>
                    <td class="border border-gray-300 text-center"></td>
                </tr>
                <tr>
                    <td class="border border-gray-300 p-2 pl-10 text-left">dr. Budi</td>
                    <td class="border border-gray-300 text-center">13:00-17:00</td>
                    <td class="border border-gray-300 text-center">13:00-17:00</td>
                    <td class="border border-gray-300 text-center">13:00-17:00</td>
                    <td class="border border-gray-300 text-center"></td>
                    <td class="border border-gray-300 text-center">13:00-17:00</td>
                    <td class="border border-gray-300 text-center"></td>
                    <td class="border border-gray-300 text-center"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection