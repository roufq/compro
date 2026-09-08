<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\SiteSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function identitas(): View
    {
        return view('admin.settings.identitas', ['settings' => SiteSetting::current()]);
    }

    public function hero(): View
    {
        return view('admin.settings.hero', ['settings' => SiteSetting::current()]);
    }

    public function kontak(): View
    {
        return view('admin.settings.kontak', ['settings' => SiteSetting::current()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['sometimes', 'required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'hero_badge_text' => ['nullable', 'string', 'max:255'],
            'hero_tile_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hero_tile_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hero_tile_3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_hero_tile_1' => ['sometimes', 'boolean'],
            'remove_hero_tile_2' => ['sometimes', 'boolean'],
            'remove_hero_tile_3' => ['sometimes', 'boolean'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'url'],
            'linkedin_url' => ['nullable', 'url'],
            'youtube_url' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_hero_image' => ['sometimes', 'boolean'],
            'hero_video_url' => [
                'bail', 'nullable', 'string', 'url:http,https', 'max:2048',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (is_string($value) && Portfolio::youtubeIdFromUrl($value) === null) {
                        $fail('Isi dengan tautan video YouTube yang valid.');
                    }
                },
            ],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_description' => ['nullable', 'string', 'max:10000'],
            'map_query' => ['nullable', 'string', 'max:255'],
        ]);

        unset($data['logo'], $data['hero_image'], $data['remove_hero_image']);

        if ($request->boolean('remove_hero_image')) {
            $data['hero_image_path'] = null;
        }

        if ($request->hasFile('hero_image')) {
            $data['hero_image_path'] = $request->file('hero_image')->store('hero', 'public');
        }

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('logo', 'public');
        }

        foreach ([1, 2, 3] as $tile) {
            unset($data["hero_tile_{$tile}"], $data["remove_hero_tile_{$tile}"]);

            if ($request->boolean("remove_hero_tile_{$tile}")) {
                $data["hero_tile_{$tile}_path"] = null;
            }

            if ($request->hasFile("hero_tile_{$tile}")) {
                $data["hero_tile_{$tile}_path"] = $request->file("hero_tile_{$tile}")->store('hero-tiles', 'public');
            }
        }

        SiteSetting::current()->update($data);

        return back()->with('status', 'Pengaturan berhasil disimpan.');
    }
}
