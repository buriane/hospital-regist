<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="text-lg font-medium">Informasi Pasien</h3>
            <p><strong>Nama:</strong> {{ $record->pasien->nama_pasien }}</p>
            <p><strong>Nomor RM:</strong> {{ $record->pasien->nomor_rm }}</p>
            <p><strong>Tanggal Lahir:</strong> 
                @if($record->pasien->tanggal_lahir instanceof \Carbon\Carbon)
                    {{ $record->pasien->tanggal_lahir->format('d/m/Y') }}
                @else
                    {{ $record->pasien->tanggal_lahir }}
                @endif
            </p>
        </div>
        <div>
            <h3 class="text-lg font-medium">Informasi Registrasi</h3>
            <p><strong>Nomor Urut:</strong> 
                <span class="inline-block px-2 py-1 text-sm font-semibold">
                    {{ $record->nomor_urut }}
                </span>
            </p>
            <p><strong>Status:</strong> 
                <span class="inline-block px-2 py-1 text-sm font-semibold rounded bg-gray-100 text-gray-700">
                    {{ $record->status }}
                </span>
            </p>
            <p><strong>Tanggal Kunjungan:</strong> 
                @if($record->tanggal_kunjungan instanceof \Carbon\Carbon)
                    {{ $record->tanggal_kunjungan->format('d/m/Y') }}
                @else
                    {{ $record->tanggal_kunjungan }}
                @endif
            </p>
            <p><strong>Kode Booking:</strong></p>
            <div class="mt-4 flex flex-col">
                {!! DNS2D::getBarcodeHTML("$record->kode_booking", 'QRCODE', 4, 4) !!}
                <span class="p-3">{{ $record->kode_booking }}</span>
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-lg font-medium">Informasi Poliklinik dan Dokter</h3>
        <p><strong>Poliklinik:</strong> {{ $record->poliklinik->nama_poliklinik }}</p>
        <p><strong>Dokter:</strong> {{ $record->dokter->nama_dokter }}</p>
        @php
            $tanggalKunjungan = $record->tanggal_kunjungan;
            $hariKunjungan = \Carbon\Carbon::parse($tanggalKunjungan)->locale('id')->dayName;
            $jamMulai = $record->jam_mulai;
            $jamSelesai = $record->jam_selesai;
            
            // Check for special schedule first
            $jadwalKhusus = App\Models\JadwalKhususDokter::where('id_dokter', $record->id_dokter)
                ->where('tanggal', $tanggalKunjungan)
                ->where('jam_mulai', $jamMulai)
                ->where('jam_selesai', $jamSelesai)
                ->first();
            
            if ($jadwalKhusus) {
                $jamPraktik = \Carbon\Carbon::parse($jadwalKhusus->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($jadwalKhusus->jam_selesai)->format('H:i');
            } else {
                // If no special schedule, check regular schedule
                $jadwalRegular = App\Models\JadwalDokter::where('id_dokter', $record->id_dokter)
                    ->where('hari', $hariKunjungan)
                    ->where('jam_mulai', $jamMulai)
                    ->where('jam_selesai', $jamSelesai)
                    ->first();
            
                if ($jadwalRegular) {
                    $jamPraktik = \Carbon\Carbon::parse($jadwalRegular->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($jadwalRegular->jam_selesai)->format('H:i');
                } else {
                    $jamPraktik = 'N/A';
                }
            }
        @endphp
        <p><strong>Jam Praktik:</strong> {{ $jamPraktik }}</p>
    </div>
</div>