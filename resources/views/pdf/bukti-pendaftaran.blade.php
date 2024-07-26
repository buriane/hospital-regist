<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Bukti Pendaftaran</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                line-height: 1.4;
                color: #333;
                margin: 0;
                padding: 0;
                text-align: center;
            }
            .container {
                width: 100%;
                max-width: 800px;
                margin: 0 auto;
                padding: 5px;
                box-sizing: border-box;
                border: 1px solid #333;
            }
            .header {
                text-align: center;
                margin-bottom: 10px;
            }
            .header h1 {
                color: #333;
                margin: 0;
                font-size: 16px;
                line-height: 1.2;
            }
            .logo {
                width: 60px;
                height: auto;
                margin: 5px auto;
                display: block;
            }
            .booking-code {
                font-size: 16px;
                margin-bottom: 10px;
            }
            .booking-code-number {
                font-size: 14px;
                margin-top: 5px;
                color: #333;
            }
            .info-grid {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }
            .info-item {
                width: 100%;
                margin-bottom: 10px;
            }
            .info-label {
                font-weight: bold;
                font-size: 12px;
            }
            .info-value {
                font-size: 12px;
            }
            .footer {
                font-style: italic;
                font-size: 11px;
                margin-top: 20px;
            }
            .qr-code {
                padding-top: 10px;
            }
            .qr-code img {
                width: 80px;
                height: 80px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div style='text-align: center;'>
                <img src="data:image/png;base64,{{ $logoData }}" class='logo' alt='Logo RSU St. Elisabeth'>
            </div>
            <div class='header'>
                Rumah Sakit Umum
                <h1>St. Elisabeth Purwokerto</h1>
            </div>
            <div class='booking-code'>
                KODE BOOKING BUKTI PENDAFTARAN ANDA
                <div class='qr-code'>
                    <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code">
                </div>
                <div class='booking-code-number'>{{ $kode }}</div>
            </div>
            <div class='info-grid'>
                <div class='info-item'>
                    <div class='info-label'>Nama Pasien</div>
                    <div class='info-value'>{{ $registrasi->pasien->nama_pasien }}</div>
                </div>
                <div class='info-item'>
                    <div class='info-label'>Tanggal Kunjungan</div>
                    <div class='info-value'>{{ $tanggal }}</div>
                </div>
                <div class='info-item'>
                    <div class='info-label'>Poliklinik</div>
                    <div class='info-value'>{{ $registrasi->poliklinik->nama_poliklinik }}</div>
                </div>
                <div class='info-item'>
                    <div class='info-label'>Dokter</div>
                    <div class='info-value'>{{ $registrasi->dokter->nama_dokter }}</div>
                </div>
                <div class='info-item'>
                    <div class='info-label'>Jam Praktik</div>
                    <div class='info-value'>{{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</div>
                </div>
                <div class='info-item'>
                    <div class='info-label'>Waktu Kedatangan</div>
                    <div class='info-value'>30 menit sebelum jadwal praktik</div>
                </div>
            </div>
            <div class='footer'>
                <p>Silakan tunjukkan bukti pendaftaran ini di loket pendaftaran.</p>
                <p>Tidak perlu mencetak/print bukti pendaftaran ini, cukup tunjukkan di layar perangkat Anda kepada petugas loket pendaftaran.</p>
            </div>
        </div>
    </body>
</html>