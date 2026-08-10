<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = SiteSetting::current();

        return view('owner.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = SiteSetting::current();

        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'hero_badge_text' => 'nullable|string|max:255',
            'hero_headline' => 'nullable|string|max:255',
            'hero_subheadline' => 'nullable|string|max:1000',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'stat_1_value' => 'nullable|string|max:20',
            'stat_1_label' => 'nullable|string|max:50',
            'stat_2_value' => 'nullable|string|max:20',
            'stat_2_label' => 'nullable|string|max:50',
            'stat_3_value' => 'nullable|string|max:20',
            'stat_3_label' => 'nullable|string|max:50',
            'footer_description' => 'nullable|string|max:1000',
            'support_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'receipt_header' => 'nullable|string|max:500',
            'receipt_footer' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('site', 'public');
        }

        if ($request->hasFile('hero_image')) {
            if ($setting->hero_image) {
                Storage::disk('public')->delete($setting->hero_image);
            }
            $validated['hero_image'] = $request->file('hero_image')->store('site', 'public');
        }

        $setting->update($validated);

        return back()->with('success', 'Pengaturan landing page berhasil diperbaharui.');
    }
}
