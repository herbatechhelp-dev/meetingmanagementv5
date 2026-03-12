<x-mail::message>
# Halo, {{ $participant->name }}

Anda telah ditunjuk sebagai **Penulis Notulensi** untuk meeting berikut:

**Judul:** {{ $meeting->title }}<br>
**Jenis Meeting:** {{ $meeting->meetingType->name ?? 'Umum' }}<br>
**Tipe Pelaksanaan:** {{ $meeting->is_online ? 'Online' : 'Tatap Muka' }}<br>
**Waktu:** {{ \Carbon\Carbon::parse($meeting->start_time)->format('l, d F Y H:i') }} - {{ \Carbon\Carbon::parse($meeting->end_time)->format('H:i') }}<br>
**Lokasi / Tautan:** {{ $meeting->location }} 
@if($meeting->is_online && $meeting->meeting_link)
<br>**Link Meeting:** [Klik di sini]({{ $meeting->meeting_link }})
@endif

Silakan persiapkan diri untuk mencatat poin-poin penting dan keputusan yang diambil selama meeting berlangsung.

<x-mail::button :url="url('/meetings/'.$meeting->id.'/running')">
Buka Halaman Notulensi
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
