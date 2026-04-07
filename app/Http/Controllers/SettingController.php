<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan branding.
     */
    public function branding()
    {
        $setting = AppSetting::first() ?? new AppSetting();
        return view('settings.branding', compact('setting'));
    }

    /**
     * Simpan pembaruan pengaturan branding.
     */
    public function updateBranding(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'login_title' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico,jpg|max:1024',
        ]);

        $setting = AppSetting::first() ?? new AppSetting();

        $setting->app_name = $request->app_name;
        $setting->login_title = $request->login_title;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            // Simpan logo baru
            $path = $request->file('logo')->store('branding', 'public');
            $setting->logo_path = $path;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Hapus favicon lama jika ada
            if ($setting->favicon_path && Storage::disk('public')->exists($setting->favicon_path)) {
                Storage::disk('public')->delete($setting->favicon_path);
            }
            // Simpan favicon baru
            $path = $request->file('favicon')->store('branding', 'public');
            $setting->favicon_path = $path;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan branding berhasil diperbarui.');
    }
}
