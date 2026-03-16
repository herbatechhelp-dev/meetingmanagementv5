<!DOCTYPE html>
<html>
<head>
    <title>Pembaruan Tindak Lanjut</title>
</head>
<body>
    <h1>Halo, {{ $participant->name }}</h1>
    <p>Ada pembaruan pada tindak lanjut yang ditugaskan kepada Anda:</p>
    <ul>
        <li><strong>Judul:</strong> {{ $actionItem->title }}</li>
        <li><strong>Status Baru:</strong> {{ ucfirst(str_replace('_', ' ', $actionItem->status)) }}</li>
        <li><strong>Tenggat Waktu:</strong> {{ \Carbon\Carbon::parse($actionItem->due_date)->format('d M Y') }}</li>
    </ul>
    <p>Silakan periksa detailnya di aplikasi.</p>
    <a href="{{ route('action-items.show', $actionItem) }}">Lihat Tindak Lanjut</a>
    <p>Terima kasih.</p>
</body>
</html>
