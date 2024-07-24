<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="text-sm font-medium">Nomor RM</h3>
            <p>{{ $record->nomor_rm ?? 'N/A' }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium">Nama Lengkap</h3>
            <p>{{ $record->nama_pasien }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="text-sm font-medium">Tempat Lahir</h3>
            <p>{{ $record->tempat_lahir }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium">Tanggal Lahir</h3>
            <p>
                @if($record->tanggal_lahir instanceof \Carbon\Carbon)
                    {{ $record->tanggal_lahir->format('d/m/Y') }}
                @elseif(is_string($record->tanggal_lahir))
                    {{ \Carbon\Carbon::parse($record->tanggal_lahir)->format('d/m/Y') }}
                @else
                    N/A
                @endif
            </p>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-medium">Jenis Kelamin</h3>
        <p>{{ $record->jenis_kelamin }}</p>
    </div>

    <div>
        <h3 class="text-sm font-medium">Alamat</h3>
        <p>{{ $record->alamat }}</p>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="text-sm font-medium">Nomor Telepon</h3>
            <p>{{ $record->nomor_telepon }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium">Email</h3>
            <p>{{ $record->email }}</p>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-medium">Nomor Kartu</h3>
        <p>{{ $record->nomor_kartu ?? 'N/A' }}</p>
    </div>
</div>