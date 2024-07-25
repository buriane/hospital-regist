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
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
            border: 2px solid #333;
            border-radius: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 18px;
            line-height: 1.2;
        }
        .logo {
            width: 80px;
            height: auto;
            margin: 10px auto;
            display: block;
        }
        .info {
            border-radius: 5px;
        }
        .booking-code {
            text-align: center;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .booking-code-number {
            display: block;
            font-size: 16px;
            margin-top: 10px;
            color: #333;
            padding-top: 10px;
        }
        .info-grid {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        .info-item {
            text-align: center;
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info-value {
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            font-style: italic;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
        }
        .booking-code-text {
            margin-bottom: 15px;
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
        <div class='info'>
            <div class='booking-code'>
                <div class='booking-code-text'>KODE BOOKING BUKTI PENDAFTARAN ANDA</div>
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
        </div>
        <div class='footer'>
            <p>Silakan tunjukkan bukti pendaftaran ini di loket pendaftaran.</p>
        </div>
    </div>
</body>
</html>