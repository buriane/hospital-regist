@extends('layouts.app')

@section('title', 'Registrasi')

@section('content')
    <div x-data="{ showFormPasienLama: false, showFormPasienBaru: false, showBookingCode: false, showModal: false }">
        <div class="flex justify-center items-center p-4 sm:p-8 relative">
            <h1 class="absolute text-light-gray text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold z-10 text-center px-2">
                <strong>Form Pendaftaran Online</strong>
            </h1>
            <img src="{{ url('pic.png') }}" alt="Gambar" class="relative w-auto h-auto">
        </div>

        <div
            class="bg-light-blue p-4 sm:p-8 rounded-2xl mt-4 sm:mt-8 mx-auto mb-8 sm:mb-16 text-center max-w-[90%] sm:max-w-screen-sm md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl">

            @if (isset($kode)){{-- Progress Bar --}}
                <div class="flex justify-center items-center mb-3 sm:mb-8 mt-3 sm:mt-8">
                    <div class="flex items-center flex-shrink-0">
                        <div
                            class="w-7 h-7 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-xs sm:text-lg shadow relative group bg-blue text-white">
                            <div
                                class="absolute inset-0 rounded-full border-[3px] sm:border-[6px] -m-[1.5px] sm:-m-1 border-blue">
                            </div>
                            <img src="{{ url('success.svg') }}" alt="Success" class="w-3.5 h-3.5 sm:w-6 sm:h-6">
                        </div>
                        <div
                            class="w-20 sm:w-24 md:w-48 lg:w-96 h-1.5 sm:h-3 shadow transition-colors duration-300 ease-linear overflow-hidden bg-gray-200">
                            <div class="h-full bg-blue transition-all duration-300 ease-linear">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center flex-shrink-0">
                        <div
                            class="w-7 h-7 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-xs sm:text-lg shadow relative group bg-blue text-white">
                            <div
                                class="absolute inset-0 rounded-full border-[3px] sm:border-[6px] -m-[1.5px] sm:-m-1 border-blue">
                            </div>
                            <img src="{{ url('success.svg') }}" alt="Success" class="w-3.5 h-3.5 sm:w-6 sm:h-6">
                        </div>
                        <div
                            class="w-20 sm:w-24 md:w-48 lg:w-96 h-1.5 sm:h-3 shadow transition-all duration-300 ease-linear overflow-hidden bg-gray-200">
                            <div class="h-full bg-blue transition-all duration-300 ease-linear"></div>
                        </div>
                    </div>
                    <div class="flex items-center flex-shrink-0">
                        <div
                            class="w-7 h-7 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-xs sm:text-lg shadow relative group bg-blue text-white">
                            <div
                                class="absolute inset-0 rounded-full border-[3px] sm:border-[6px] -m-[1.5px] sm:-m-1 border-blue">
                            </div>
                            <img src="{{ url('success.svg') }}" alt="Success" class="w-3.5 h-3.5 sm:w-6 sm:h-6">
                        </div>
                    </div>
                </div>
                {{-- End Progress Bar --}}
                <div class="mt-8 px-4 sm:px-0">
                    {{-- Kode Booking --}}
                    <div class="p-4 sm:p-8 md:p-12 max-w-5xl mx-auto text-sm sm:text-base md:text-lg">
                        <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 md:mb-12">Terima kasih telah menggunakan
                            layanan
                            kami.</p>
                        <h1 class="text-green font-bold text-2xl sm:text-3xl mb-6 sm:mb-8 md:mb-12"><strong>Kode Booking :
                                {{ $kode }}</strong></h1>
                        <p class="text-base sm:text-lg md:text-xl">Silakan melakukan daftar ulang di loket pendaftaran,
                            besok
                            tanggal <strong class="bg-yellow-200">{{ $besok }}</strong> dengan menunjukkan kode
                            booking, <strong class="bg-yellow-200">30 menit</strong> sebelum jadwal praktik</p>
                    </div>
                    {{-- End Kode Booking --}}
                </div>
            @else
                <div class="flex justify-center items-center mb-3 sm:mb-8 mt-3 sm:mt-8">
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-7 h-7 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-xs sm:text-lg shadow relative group transition-colors duration-200 ease-linear"
                            :class="{
                                'bg-blue text-white': showFormPasienLama || showFormPasienBaru ||
                                    showBookingCode,
                                'bg-light-gray text-dark-grey': !showFormPasienLama && !
                                    showFormPasienBaru && !showBookingCode
                            }">
                            <div
                                class="absolute inset-0 rounded-full border-[3px] sm:border-[6px] -m-[1.5px] sm:-m-1 transition-colors duration-200 ease-linear border-blue">
                            </div>
                            <template x-if="!showFormPasienLama && !showFormPasienBaru && !showBookingCode"
                                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0">
                                <span>1</span>
                            </template>
                            <template x-if="showFormPasienLama || showFormPasienBaru || showBookingCode"
                                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100">
                                <img src="{{ url('success.svg') }}" alt="Success" class="w-3.5 h-3.5 sm:w-6 sm:h-6">
                            </template>
                        </div>
                        <div
                            class="w-20 sm:w-24 md:w-48 lg:w-96 h-1.5 sm:h-3 shadow transition-colors duration-300 ease-linear overflow-hidden bg-gray-200">
                            <div class="h-full bg-blue transition-all duration-300 ease-linear"
                                :class="{
                                    'w-full': showFormPasienLama || showFormPasienBaru || showBookingCode,
                                    'w-0': !
                                        showFormPasienLama && !showFormPasienBaru && !showBookingCode
                                }">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-7 h-7 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-xs sm:text-lg shadow relative group transition-colors duration-200 ease-linear"
                            :class="{ 'bg-blue text-white': showBookingCode, 'bg-light-gray text-dark-grey': !showBookingCode }">
                            <div class="absolute inset-0 rounded-full border-[3px] sm:border-[6px] -m-[1.5px] sm:-m-1 transition-colors duration-200 ease-linear"
                                :class="{
                                    'border-blue': showBookingCode || showFormPasienLama ||
                                        showFormPasienBaru,
                                    'border-gray-200': !showFormPasienLama && !showFormPasienBaru && !
                                        showBookingCode
                                }">
                            </div>
                            <template x-if="!showBookingCode" x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                <span>2</span>
                            </template>
                            <template x-if="showBookingCode" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <img src="{{ url('success.svg') }}" alt="Success" class="w-3.5 h-3.5 sm:w-6 sm:h-6">
                            </template>
                        </div>
                        <div
                            class="w-20 sm:w-24 md:w-48 lg:w-96 h-1.5 sm:h-3 shadow transition-all duration-300 ease-linear overflow-hidden bg-gray-200">
                            <div class="h-full bg-blue transition-all duration-300 ease-linear"
                                :class="{ 'w-full': showBookingCode, 'w-0': !showBookingCode }"></div>
                        </div>
                    </div>
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-7 h-7 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-xs sm:text-lg shadow relative group transition-colors duration-200 ease-linear"
                            :class="{ 'bg-blue text-white': showBookingCode, 'bg-light-gray text-dark-grey': !showBookingCode }">
                            <div class="absolute inset-0 rounded-full border-[3px] sm:border-[6px] -m-[1.5px] sm:-m-1 transition-colors duration-200 ease-linear"
                                :class="{ 'border-blue': showBookingCode, 'border-gray-200': !showBookingCode }"></div>
                            <template x-if="!showBookingCode" x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                <span>3</span>
                            </template>
                            <template x-if="showBookingCode" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <img src="{{ url('success.svg') }}" alt="Success" class="w-3.5 h-3.5 sm:w-6 sm:h-6">
                            </template>
                        </div>
                    </div>
                </div>
                {{-- End Progress Bar --}}

                <div x-show="!showFormPasienLama && !showFormPasienBaru && !showBookingCode">
                    <p class="max-w-2xl mx-auto text-base sm:text-lg px-4 sm:px-0">Selamat datang di sistem pendaftaran
                        online
                        kami. Pendaftaran yang Anda lakukan hari ini berlaku untuk kunjungan <strong
                            class="bg-yellow-200">BESOK</strong>. Silakan klik tombol jenis pasien sesuai dengan jenis
                        kunjungan
                        Anda.</p>

                    <div class="mt-8 mb-8 space-y-4 md:space-y-0 md:space-x-4">
                        <button @click="showFormPasienLama = true"
                            class="bg-blue text-light-gray text-xl md:text-2xl px-4 md:px-6 py-2 md:py-3 rounded-2xl w-full md:w-auto transition duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg hover:bg-light-gray hover:text-blue">
                            Pasien Lama
                        </button>
                        <button @click="showFormPasienBaru = true" id="btn-baru"
                            class="bg-green text-light-gray text-xl md:text-2xl px-4 md:px-6 py-2 md:py-3 rounded-2xl w-full md:w-auto transition duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg hover:bg-light-gray hover:text-green">
                            Pasien Baru
                        </button>
                    </div>
                </div>

                <form x-show="showFormPasienLama" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-90"
                    x-transition:enter-end="opacity-100 transform scale-100" action="" method="POST"
                    class="mt-8 px-4 sm:px-0">
                    @csrf
                    {{-- Form Pasien Lama --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center">
                        <div class="w-full sm:w-1/6"></div>
                        <div class="w-full sm:w-5/6 flex flex-col items-start">
                            <label for="nomor_rm" class="text-base sm:text-lg mb-2 text-left w-full">
                                Nomor Rekam Medis<span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" id="nomor_rm" name="nomor_rm" maxlength="6"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-4"
                                required>

                            <label for="tanggal_lahir" class="text-base sm:text-lg mb-2 text-left w-full">
                                Tanggal Lahir<span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6"
                                required>

                            <div class="w-full sm:w-5/6 flex justify-center space-x-4 mt-4">
                                <button @click="showFormPasienLama = false; showFormPasienBaru = false;" type="button"
                                    class="bg-light-gray text-dark-gray px-4 py-2 rounded-xl border-2 border-blue hover:bg-light-gray/30 hover:text-dark-gray/30 hover:border-blue/30 transition duration-200">
                                    Kembali
                                </button>
                                <button @click="showFormPasienBaru = true; showFormPasienLama = false;" type="button"
                                    id="berikutnya"
                                    class="bg-blue text-white px-4 py-2 rounded-xl hover:bg-blue/50 hover:text-white transition duration-200 hidden">
                                    Berikutnya
                                </button>
                                <button @click="showModal = true" type="button"
                                    id="berikutnyaFalse"
                                    class="bg-blue text-white px-4 py-2 rounded-xl hover:bg-blue/50 hover:text-white transition duration-200">
                                    Berikutnya
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- End Form Pasien Lama --}}
                </form>

                <form x-show="showFormPasienBaru" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-90"
                    x-transition:enter-end="opacity-100 transform scale-100" action="" method="POST"
                    class="mt-8 px-4 sm:px-0">
                    @csrf
                    {{-- Form Pasien Baru --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center">
                        <div class="w-full sm:w-1/6"></div>
                        <div class="w-full sm:w-5/6 flex flex-col items-start">
                            <input type="hidden" id="id_pasien" name="id_pasien">
                            <label for="nama_pasien" class="text-base sm:text-lg mb-2 text-left w-full">
                                Nama Lengkap<span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" id="nama_pasien_baru" name="nama_pasien"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-4"
                                required>

                            <label for="tempat_lahir" class="text-base sm:text-lg mb-2 text-left w-full">
                                Tempat Lahir<span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" id="tempat_lahir_baru" name="tempat_lahir"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6"
                                required>

                            <label for="tanggal_lahir" class="text-base sm:text-lg mb-2 text-left w-full">
                                Tanggal Lahir<span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="date" id="tanggal_lahir_baru" name="tanggal_lahir"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6"
                                required>

                            <label class="text-base sm:text-lg mb-2 text-left w-full">
                                Jenis Kelamin<span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="flex flex-col sm:flex-row w-full sm:w-5/6 mb-6">
                                <div class="flex items-center mr-4 mb-2 sm:mb-0">
                                    <input type="radio" id="laki-laki" name="jenis_kelamin" value="Laki-laki"
                                        class="hidden" required>
                                    <label for="laki-laki" class="flex items-center cursor-pointer">
                                        <span class="inline-block mr-2 rounded-full border-2 border-dark-gray/50"></span>
                                        Laki-laki
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="perempuan" name="jenis_kelamin" value="Perempuan"
                                        class="hidden" required>
                                    <label for="perempuan" class="flex items-center cursor-pointer">
                                        <span class="inline-block mr-2 rounded-full border-2 border-dark-gray/50"></span>
                                        Perempuan
                                    </label>
                                </div>
                            </div>

                            <label for="alamat" class="text-base sm:text-lg mb-2 text-left w-full">
                                Alamat
                            </label>
                            <input type="text" id="alamat_baru" name="alamat"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6">

                            <label for="nomor_telepon" class="text-base sm:text-lg mb-2 text-left w-full">
                                Nomor Telepon<span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" id="nomor_telepon_baru" name="nomor_telepon"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6"
                                required>

                            <label for="email" class="text-base sm:text-lg mb-2 text-left w-full">
                                Email<span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="email" id="email_baru" name="email"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6"
                                required>

                            <label for="nomor_kartu" class="text-base sm:text-lg mb-2 text-left w-full">
                                Nomor Kartu
                            </label>
                            <input type="number" id="nomor_kartu_baru" name="nomor_kartu"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6">

                            <label for="tanggal_kunjungan" class="text-base sm:text-lg mb-2 text-left w-full">
                                Tanggal Kunjungan<span class="text-red-500 ml-1 text-xl sm:text-2xl font-bold">*</span>
                            </label>
                            <input type="date" id="tanggal_kunjungan_baru" name="tanggal_kunjungan"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6"
                                required>

                            <label for="poliklinik" class="text-base sm:text-lg mb-2 text-left w-full">
                                Poliklinik<span class="text-red-500 ml-1">*</span>
                            </label>
                            <select id="poliklinik" name="poliklinik"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6"
                                required>
                                <option value="" disabled selected>Pilih Poliklinik</option>
                                @foreach ($polikliniks as $poliklinik)
                                    <option value="{{ $poliklinik->id_poliklinik }}">{{ $poliklinik->nama_poliklinik }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="dokter" class="text-base sm:text-lg mb-2 text-left w-full">
                                Dokter<span class="text-red-500 ml-1">*</span>
                            </label>
                            <select id="dokter" name="dokter"
                                class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6"
                                required>
                                <option value="" disabled selected>Pilih Dokter</option>
                                @foreach ($jadwal as $dokter)
                                    <option value="{{ $dokter->dokter->id_dokter }}"
                                        data-poliklinik="{{ $dokter->dokter->id_poliklinik }}"
                                        data-tanggal="{{ $dokter->tanggal }}"
                                        data-kuota="{{ $dokter->kuota }}">
                                        {{ $dokter->dokter->nama_dokter }} |
                                        {{ date('H:i', strtotime($dokter->jam_mulai)) }} -
                                        {{ date('H:i', strtotime($dokter->jam_selesai)) }}
                                        (Kuota: {{ $dokter->kuota }})
                                    </option>
                                @endforeach
                            </select>

                            <div class="w-full sm:w-5/6 flex justify-center space-x-4 mt-4">
                                <button @click="showFormPasienBaru = false" type="button"
                                    class="bg-light-gray text-dark-gray px-4 py-2 rounded-xl border-2 border-blue hover:bg-light-gray/30 hover:text-dark-gray/30 hover:border-blue/30 transition duration-200">Kembali</button>
                                <button @click="validateForm()" type="submit"
                                    class="bg-blue text-white px-4 py-2 rounded-xl hover:bg-blue/50 hover:text-white transition duration-200">
                                    Daftar
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- End Form Pasien Baru --}}
                </form>
            @endif

        </div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Data Pasien Tidak Ditemukan
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Maaf, data pasien Anda tidak ditemukan. Silakan periksa kembali nomor rekam medis dan tanggal lahir yang Anda masukkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="showModal = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
    </div>
@endsection

@section('javascript')
<script type="text/javascript">
document.addEventListener('alpine:init', () => {
    Alpine.data('formData', () => ({
        showModal: false,
        showPatientNotFoundModal() {
            this.showModal = true;
        }
    }));

    document.addEventListener('DOMContentLoaded', function() {
        const nomor_rm = document.getElementById('nomor_rm');
        const tanggal_lahir = document.getElementById('tanggal_lahir');
        const patientData = document.getElementById('patientData');

        function limitInputLength(event) {
            if (event.target.value.length > 6) {
                event.target.value = event.target.value.slice(0, 6);
            }
        }
        nomor_rm.addEventListener('input', limitInputLength);

        function checkPatient() {
            if (nomor_rm.value && tanggal_lahir.value) {
                fetch(
                        `/check-patient?nomor_rm=${nomor_rm.value}&tanggal_lahir=${tanggal_lahir.value}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            document.getElementById('id_pasien').value = data.id_pasien;
                            document.getElementById('nama_pasien_baru').value = data
                                .nama_pasien;
                            document.getElementById('tempat_lahir_baru').value = data
                                .tempat_lahir;
                            document.getElementById('tanggal_lahir_baru').value = data
                                .tanggal_lahir;
                            if (data.jenis_kelamin == "Perempuan") {
                                document.querySelector(
                                        'input[name="jenis_kelamin"][value="Perempuan"]')
                                    .checked = true;
                                document.querySelector(
                                        'input[name="jenis_kelamin"][value="Laki-laki"]')
                                    .disabled = true;
                            } else {
                                document.querySelector(
                                        'input[name="jenis_kelamin"][value="Laki-laki"]')
                                    .checked = true;
                                document.querySelector(
                                        'input[name="jenis_kelamin"][value="Perempuan"]')
                                    .disabled = true;

                            }
                            document.getElementById('alamat_baru').value = data.alamat;
                            document.getElementById('nomor_telepon_baru').value = data
                                .nomor_telepon;
                            document.getElementById('email_baru').value = data.email;
                            document.getElementById('nomor_kartu_baru').value = data
                                .nomor_kartu;
                            document.getElementById('berikutnya').classList.remove('hidden');
                            document.getElementById('berikutnyaFalse').classList.add('hidden');

                            document.getElementById('nama_pasien_baru').setAttribute('readonly',
                                true);
                            document.getElementById('tempat_lahir_baru').setAttribute(
                                'readonly', true);
                            document.getElementById('tanggal_lahir_baru').setAttribute(
                                'readonly', true);
                            document.getElementById('alamat_baru').setAttribute('readonly',
                                true);
                            document.getElementById('nomor_telepon_baru').setAttribute(
                                'readonly', true);
                            document.getElementById('email_baru').setAttribute('readonly',
                                true);
                            document.getElementById('nomor_kartu_baru').setAttribute('readonly',
                                true);
                        } else {
                            // patientData.classList.add('hidden');
                            document.getElementById('berikutnya').classList.add('hidden');
                            document.getElementById('berikutnyaFalse').classList.remove(
                                'hidden');
                            Alpine.store('formData').showPatientNotFoundModal();
                        }
                    });
            }
        }

        function openAll() {
            document.getElementById('nama_pasien_baru').removeAttribute('readonly');
            document.getElementById('tempat_lahir_baru').removeAttribute('readonly');
            document.getElementById('tanggal_lahir_baru').removeAttribute('readonly');
            document.getElementById('alamat_baru').removeAttribute('readonly');
            document.getElementById('nomor_telepon_baru').removeAttribute('readonly');
            document.getElementById('email_baru').removeAttribute('readonly');
            document.getElementById('nomor_kartu_baru').removeAttribute('readonly');

            document.querySelector(
                    'input[name="jenis_kelamin"][value="Perempuan"]')
                .checked = false;
            document.querySelector(
                    'input[name="jenis_kelamin"][value="Laki-laki"]')
                .disabled = false;
            document.querySelector(
                    'input[name="jenis_kelamin"][value="Laki-laki"]')
                .checked = false;
            document.querySelector(
                    'input[name="jenis_kelamin"][value="Perempuan"]')
                .disabled = false;

            document.getElementById('id_pasien').value = null;
            document.getElementById('nama_pasien_baru').value = null;
            document.getElementById('tempat_lahir_baru').value = null;
            document.getElementById('tanggal_lahir_baru').value = null;

            document.getElementById('alamat_baru').value = null;
            document.getElementById('nomor_telepon_baru').value = null;
            document.getElementById('email_baru').value = null;
            document.getElementById('nomor_kartu_baru').value = null;

            document.getElementById('berikutnya').classList.add('hidden');
            document.getElementById('berikutnyaFalse').classList.remove(
                'hidden');
        }
        nomor_rm.addEventListener('blur', checkPatient);
        tanggal_lahir.addEventListener('change', checkPatient);
        document.getElementById('btn-baru').addEventListener('click', openAll);
    });
})
</script>
@endsection