<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingsController extends Controller
{
    /**
     * Definisi semua jenis notifikasi yang bisa diatur.
     */
    private function getNotificationTypes(): array
    {
        return [
            'meeting_invitation' => 'Undangan & Perubahan Meeting',
            'minute_taker'       => 'Ditunjuk sebagai Penulis Notulensi',
            'action_taker'       => 'Ditunjuk sebagai Penulis Tindak Lanjut',
            'action_item_assigned' => 'Tugas Tindak Lanjut Baru Diberikan',
            'action_item_updated'  => 'Detail Tugas Diperbarui',
            'action_item_review'   => 'Tugas Dilaporkan Selesai (untuk Organizer)',
            'action_item_verified' => 'Tugas Anda Diverifikasi Selesai',
            'action_item_revision' => 'Tugas Anda Diminta Revisi',
        ];
    }

    public function index()
    {
        $user  = Auth::user();
        $prefs = $user->notification_preferences ?? [];
        $types = $this->getNotificationTypes();

        return view('notifications.settings', compact('prefs', 'types'));
    }

    public function update(Request $request)
    {
        $user  = Auth::user();
        $types = $this->getNotificationTypes();

        $newPrefs = [];
        foreach (array_keys($types) as $type) {
            $newPrefs["{$type}_bell"]  = $request->boolean("{$type}_bell");
            $newPrefs["{$type}_email"] = $request->boolean("{$type}_email");
        }

        $user->update(['notification_preferences' => $newPrefs]);

        return redirect()->route('notifications.settings')
            ->with('success', 'Preferensi notifikasi berhasil disimpan!');
    }
}
