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
            <p><strong>Kode Booking:</strong> {{ $record->kode_booking }}</p>
            <p><strong>Tanggal Kunjungan:</strong> 
                @if($record->tanggal_kunjungan instanceof \Carbon\Carbon)
                    {{ $record->tanggal_kunjungan->format('d/m/Y') }}
                @else
                    {{ $record->tanggal_kunjungan }}
                @endif
            </p>
            <p><strong>Status:</strong> 
                <span class="inline-block px-2 py-1 text-sm font-semibold rounded bg-gray-100 text-gray-700">
                    {{ $record->status }}
                </span>
            </p>
        </div>
    </div>

    <div>
        <h3 class="text-lg font-medium">Informasi Poliklinik dan Dokter</h3>
        <p><strong>Poliklinik:</strong> {{ $record->poliklinik->nama_poliklinik }}</p>
        <p><strong>Dokter:</strong> {{ $record->dokter->nama_dokter }}</p>
        @php
            $jadwal = App\Models\JadwalDokter::where('id_dokter', $record->id_dokter)
                ->whereDate('tanggal', $record->tanggal_kunjungan)
                ->first();
        @endphp
        @if($jadwal)
            <p><strong>Jam Praktik:</strong> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</p>
        @endif
    </div>
</div>