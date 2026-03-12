<x-mail::message>
# Halo, {{ $participant->name }}

Anda telah diundang ke meeting baru. Berikut adalah detail meeting tersebut:

**Judul:** {{ $meeting->title }}<br>
**Jenis Meeting:** {{ $meeting->meetingType->name ?? 'Umum' }}<br>
**Tipe Pelaksanaan:** {{ $meeting->is_online ? 'Online' : 'Tatap Muka' }}<br>
**Waktu:** {{ \Carbon\Carbon::parse($meeting->start_time)->format('l, d F Y H:i') }} - {{ \Carbon\Carbon::parse($meeting->end_time)->format('H:i') }}<br>
**Lokasi / Tautan:** {{ $meeting->location }} 
@if($meeting->is_online && $meeting->meeting_link)
<br>**Link Meeting:** [Klik di sini]({{ $meeting->meeting_link }})
@endif
@if($meeting->description)
<br><br>**Deskripsi Meeting:**
{{ $meeting->description }}
@endif

<x-mail::button :url="url('/meetings/'.$meeting->id)">
Lihat Detail Meeting
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
