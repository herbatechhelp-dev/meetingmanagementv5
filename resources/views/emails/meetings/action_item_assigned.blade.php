<x-mail::message>
# Halo, {{ $participant->name }}

Anda telah ditugaskan untuk sebuah **Tindak Lanjut** baru dari meeting **{{ $meeting->title }}**.

**Judul Tugas:** {{ $actionItem->title }}<br>
**Prioritas:** {{ $actionItem->priority_label }}<br>
**Batas Waktu:** {{ \Carbon\Carbon::parse($actionItem->due_date)->format('l, d F Y') }}<br>

**Deskripsi:**
{{ $actionItem->description }}

Silakan segera selesaikan tugas ini dan perbarui statusnya melalui sistem.

<x-mail::button :url="url('/action-items/'.$actionItem->id)">
Lihat Detail Tugas
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
