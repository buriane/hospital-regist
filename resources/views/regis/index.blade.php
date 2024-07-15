@extends('layouts.app')

@section('title', 'Registrasi')

@section('content')
<div x-data="{ showFormPasienLama: false, showFormPasienBaru: false, showBookingCode: false }">
    <div class="flex justify-center items-center p-8 relative">
        <h1 class="absolute text-light-gray text-2xl sm:text-3xl md:text-4xl font-bold z-10"><strong>Form Pendaftaran Online</strong></h1>
        <img src="{{ url('pic.png') }}" alt="Gambar" class="relative">
    </div>

    <div class="bg-light-blue p-4 sm:p-8 rounded-2xl mt-4 sm:mt-8 mx-auto mb-8 sm:mb-16 text-center max-w-[90%] sm:max-w-screen-sm md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl">
        {{-- Progress Bar --}}
        <div class="flex justify-center items-center mb-4 sm:mb-8 mt-4 sm:mt-8">
            <div class="flex items-center flex-shrink-0">
                <div class="w-8 h-8 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-sm sm:text-lg shadow relative group transition-colors duration-200 ease-linear"
                    :class="{ 'bg-blue text-white': showFormPasienLama || showFormPasienBaru || showBookingCode, 'bg-light-gray text-dark-grey': !showFormPasienLama && !showFormPasienBaru && !showBookingCode }">
                    <div class="absolute inset-0 rounded-full border-[4px] sm:border-[6px] -m-[2px] sm:-m-1 transition-colors duration-200 ease-linear border-blue"></div>
                    <template x-if="!showFormPasienLama && !showFormPasienBaru && !showBookingCode" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <span>1</span>
                    </template>
                    <template x-if="showFormPasienLama || showFormPasienBaru || showBookingCode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <img src="{{ url('success.svg') }}" alt="Success" class="w-4 h-4 sm:w-6 sm:h-6">
                    </template>
                </div>
                <div class="w-36 sm:w-24 md:w-48 lg:w-96 h-2 sm:h-3 shadow transition-colors duration-300 ease-linear overflow-hidden bg-gray-200">
                    <div class="h-full bg-blue transition-all duration-300 ease-linear"
                        :class="{ 'w-full': showFormPasienLama || showFormPasienBaru || showBookingCode, 'w-0': !showFormPasienLama && !showFormPasienBaru && !showBookingCode }"></div>
                </div>
            </div>
            <div class="flex items-center flex-shrink-0">
                <div class="w-8 h-8 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-sm sm:text-lg shadow relative group transition-colors duration-200 ease-linear"
                    :class="{ 'bg-blue text-white': showBookingCode, 'bg-light-gray text-dark-grey': !showBookingCode }">
                    <div class="absolute inset-0 rounded-full border-[4px] sm:border-[6px] -m-[2px] sm:-m-1 transition-colors duration-200 ease-linear"
                        :class="{ 'border-blue': showBookingCode || showFormPasienLama || showFormPasienBaru, 'border-gray-200': !showFormPasienLama && !showFormPasienBaru && !showBookingCode }"></div>
                    <template x-if="!showBookingCode" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <span>2</span>
                    </template>
                    <template x-if="showBookingCode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <img src="{{ url('success.svg') }}" alt="Success" class="w-4 h-4 sm:w-6 sm:h-6">
                    </template>
                </div>
                <div class="w-36 sm:w-24 md:w-48 lg:w-96 h-2 sm:h-3 shadow transition-all duration-300 ease-linear overflow-hidden bg-gray-200">
                    <div class="h-full bg-blue transition-all duration-300 ease-linear"
                        :class="{ 'w-full': showBookingCode, 'w-0': !showBookingCode }"></div>
                </div>
            </div>
            <div class="flex items-center flex-shrink-0">
                <div class="w-8 h-8 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-sm sm:text-lg shadow relative group transition-colors duration-200 ease-linear"
                    :class="{ 'bg-blue text-white': showBookingCode, 'bg-light-gray text-dark-grey': !showBookingCode }">
                    <div class="absolute inset-0 rounded-full border-[4px] sm:border-[6px] -m-[2px] sm:-m-1 transition-colors duration-200 ease-linear"
                        :class="{ 'border-blue': showBookingCode, 'border-gray-200': !showBookingCode }"></div>
                    <template x-if="!showBookingCode" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <span>3</span>
                    </template>
                    <template x-if="showBookingCode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <img src="{{ url('success.svg') }}" alt="Success" class="w-4 h-4 sm:w-6 sm:h-6">
                    </template>
                </div>
            </div>
        </div>
        {{-- End Progress Bar --}}
        
        <div x-show="!showFormPasienLama && !showFormPasienBaru && !showBookingCode">
            <p class="max-w-2xl mx-auto text-base sm:text-lg px-4 sm:px-0">Selamat datang di sistem pendaftaran online kami. Pendaftaran yang Anda lakukan hari ini berlaku untuk kunjungan <strong class="bg-yellow-200">BESOK</strong>. Silakan klik tombol jenis pasien sesuai dengan jenis kunjungan Anda.</p>
            
            <div class="mt-8 mb-8 space-y-4 md:space-y-0 md:space-x-4">
                <button 
                    @click="showFormPasienLama = true" 
                    class="bg-blue text-light-gray text-xl md:text-2xl px-4 md:px-6 py-2 md:py-3 rounded-2xl w-full md:w-auto transition duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg hover:bg-light-gray hover:text-blue"
                >
                    Pasien Lama
                </button>
                <button 
                    @click="showFormPasienBaru = true"
                    class="bg-green text-light-gray text-xl md:text-2xl px-4 md:px-6 py-2 md:py-3 rounded-2xl w-full md:w-auto transition duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg hover:bg-light-gray hover:text-green"
                >
                    Pasien Baru
                </button>
            </div>
        </div>

        <form x-show="showFormPasienLama" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" action="" method="POST" class="mt-8 px-4 sm:px-0">
        {{-- Form Pasien Lama --}}
        <div class="flex flex-col sm:flex-row items-center justify-center">
            <div class="w-full sm:w-1/6"></div>
            <div class="w-full sm:w-5/6 flex flex-col items-start">
                <label for="rekam_medis" class="text-base sm:text-lg mb-2 text-left w-full">
                    Nomor Rekam Medis<span class="text-red-500 ml-1">*</span>
                </label>
                <input type="number" id="rekam_medis" name="rekam_medis" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-4" required>
                
                <label for="tanggal_lahir" class="text-base sm:text-lg mb-2 text-left w-full">
                    Tanggal Lahir<span class="text-red-500 ml-1">*</span>
                </label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6" required>
                
                <div class="w-full sm:w-5/6 flex justify-center space-x-4 mt-4">
                    <button 
                        @click="showFormPasienLama = false; showFormPasienBaru = false;" 
                        type="button" 
                        class="bg-light-gray text-dark-gray px-4 py-2 rounded-xl border-2 border-blue hover:bg-light-gray/30 hover:text-dark-gray/30 hover:border-blue/30 transition duration-200"
                    >
                        Kembali
                    </button>
                    <button
                        @click="showFormPasienBaru = true; showFormPasienLama = false;"
                        type="button" 
                        class="bg-blue text-white px-4 py-2 rounded-xl hover:bg-blue/50 hover:text-white transition duration-200"
                    >
                        Berikutnya
                    </button>
                </div>
            </div>
        </div>
        {{-- End Form Pasien Lama --}}
        </form>

        <form x-show="showFormPasienBaru" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" action="" method="POST" class="mt-8 px-4 sm:px-0">
            {{-- Form Pasien Baru --}}
            <div class="flex flex-col sm:flex-row items-center justify-center">
                <div class="w-full sm:w-1/6"></div>
                <div class="w-full sm:w-5/6 flex flex-col items-start">
                    <label for="nama" class="text-base sm:text-lg mb-2 text-left w-full">
                        Nama<span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-4" required>
                    
                    <label for="tempat_lahir" class="text-base sm:text-lg mb-2 text-left w-full">
                        Tempat Lahir<span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6" required>
    
                    <label for="tanggal_lahir" class="text-base sm:text-lg mb-2 text-left w-full">
                        Tanggal Lahir<span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6" required>
    
                    <label class="text-base sm:text-lg mb-2 text-left w-full">
                        Jenis Kelamin<span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex flex-col sm:flex-row w-full sm:w-5/6 mb-6">
                        <div class="flex items-center mr-4 mb-2 sm:mb-0">
                            <input type="radio" id="laki-laki" name="jenis_kelamin" value="Laki-laki" class="hidden" required>
                            <label for="laki-laki" class="flex items-center cursor-pointer">
                                <span class="inline-block mr-2 rounded-full border-2 border-dark-gray/50"></span>
                                Laki-laki
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="perempuan" name="jenis_kelamin" value="Perempuan" class="hidden" required>
                            <label for="perempuan" class="flex items-center cursor-pointer">
                                <span class="inline-block mr-2 rounded-full border-2 border-dark-gray/50"></span>
                                Perempuan
                            </label>
                        </div>
                    </div>
    
                    <label for="alamat" class="text-base sm:text-lg mb-2 text-left w-full">
                        Alamat
                    </label>
                    <input type="text" id="alamat" name="alamat" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6">
    
                    <label for="nomor_telepon" class="text-base sm:text-lg mb-2 text-left w-full">
                        Nomor Telepon<span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="number" id="nomor_telepon" name="nomor_telepon" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6" required>
    
                    <label for="email" class="text-base sm:text-lg mb-2 text-left w-full">
                        Email<span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="email" id="email" name="email" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6" required>
                    
                    <label for="nomor_kartu" class="text-base sm:text-lg mb-2 text-left w-full">
                        Nomor Kartu
                    </label>
                    <input type="number" id="nomor_kartu" name="nomor_kartu" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6">
    
                    <label for="tanggal_kunjungan" class="text-base sm:text-lg mb-2 text-left w-full">
                        Tanggal Kunjungan<span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6" required>
    
                    <label for="poliklinik" class="text-base sm:text-lg mb-2 text-left w-full">
                        Poliklinik<span class="text-red-500 ml-1">*</span>
                    </label>
                    <select id="poliklinik" name="poliklinik" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6" required>
                        <option value="" disabled selected>Pilih Poliklinik</option>
                        <option value="poli_umum">Poliklinik Umum</option>
                        <option value="poli_gigi">Poliklinik Gigi</option>
                        <option value="poli_mata">Poliklinik Mata</option>
                    </select>
                    
                    <label for="dokter" class="text-base sm:text-lg mb-2 text-left w-full">
                        Dokter<span class="text-red-500 ml-1">*</span>
                    </label>
                    <select id="dokter" name="dokter" class="w-full sm:w-5/6 border-2 border-dark-gray/50 shadow-sm rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue/50 focus:border-transparent mb-6" required>
                        <option value="" disabled selected>Pilih Dokter</option>
                        <option value="dr_andi_08_12">dr. Andi | 08:00 - 12:00</option>
                        <option value="dr_budi_13_17">dr. Budi | 13:00 - 17:00</option>
                    </select>

                    <div class="w-full sm:w-5/6 flex justify-center space-x-4 mt-4">
                        <button @click="showFormPasienBaru = false" type="button" class="bg-light-gray text-dark-gray px-4 py-2 rounded-xl border-2 border-blue hover:bg-light-gray/30 hover:text-dark-gray/30 hover:border-blue/30 transition duration-200">Kembali</button>
                        <button 
                            @click="showFormPasienBaru = false; showBookingCode = true;" 
                            type="button" 
                            class="bg-blue text-white px-4 py-2 rounded-xl hover:bg-blue/50 hover:text-white transition duration-200"
                        >
                            Daftar
                        </button>
                    </div>
                </div>
            </div>
            {{-- End Form Pasien Baru --}}
        </form>

        <div x-show="showBookingCode" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" class="mt-8 px-4 sm:px-0">
            {{-- Kode Booking --}}
            <div class="p-4 sm:p-8 md:p-12 max-w-5xl mx-auto text-sm sm:text-base md:text-lg">
                <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 md:mb-12">Terima kasih telah menggunakan layanan kami.</p>
                <h1 class="text-green font-bold text-2xl sm:text-3xl mb-6 sm:mb-8 md:mb-12"><strong>Kode Booking : be856d</strong></h1>
                <p class="text-base sm:text-lg md:text-xl">Silakan melakukan daftar ulang di loket pendaftaran, besok tanggal <strong class="bg-yellow-200">12-07-2024</strong> dengan menunjukkan kode booking, <strong class="bg-yellow-200">30 menit</strong> sebelum jadwal praktik</p>
            </div>
            {{-- End Kode Booking --}}
        </div>
    </div>
</div>
@endsection