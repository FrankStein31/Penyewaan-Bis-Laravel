<!DOCTYPE html>
<html>
<head>
    <title>Status Penyewaan Bus</title>
</head>
<body>
    <h2>Status Penyewaan Bus</h2>
    <p>Halo {{ $rental->user->firstname }},</p>
    
    <p>Penyewaan bus dengan kode: <strong>{{ $rental->rental_code }}</strong> telah {{ $statusMessage }}.</p>
    
    @if($additionalMessage)
        <p>{{ $additionalMessage }}</p>
    @endif

    <p>Detail Penyewaan:</p>
    <ul>
        <ul>
            <p>Detail Bus</p>
            <li>Nomor Plat: {{ $rental->bus->plate_number }}</li>
            <li>Tipe: {{ $rental->bus->type }}</li>
            <li>Kapasitas: {{ $rental->bus->capacity }} Seat</li>
        </ul>
        <li>Tanggal Mulai: {{ $rental->start_date }}</li>
        <li>Total Harga: Rp {{ number_format($rental->total_price) }}</li>
    </ul>

    <p>Terima kasih telah menggunakan layanan kami.</p>
</body>
</html>