<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\SiteSetting;
use App\Rules\MediaUrlList;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    public function klien(): View
    {
        return view('admin.settings.klien', ['settings' => SiteSetting::current()]);
    }

    public function originalIp(): View
    {
        return view('admin.settings.original-ip', ['settings' => SiteSetting::current()]);
    }

    public function tim(): View
    {
        return view('admin.settings.tim', ['settings' => SiteSetting::current()]);
    }

    public function produk(): View
    {
        return view('admin.settings.produk', ['settings' => SiteSetting::current()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['sometimes', 'required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
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
            'clients' => ['nullable', 'array', 'max:100'],
            'clients.*' => ['array:name,image_url,photo'],
            'clients.*.name' => ['required', 'string', 'max:255'],
            'clients.*.image_url' => ['nullable', 'url:http,https', 'max:2048'],
            'clients.*.photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'original_ips' => ['nullable', 'array', 'max:100'],
            'original_ips.*' => ['array:name,description,image_url,photo,url,slug,details,gallery_urls,video_urls,photos'],
            'original_ips.*.name' => ['required', 'string', 'max:255'],
            'original_ips.*.description' => ['nullable', 'string', 'max:1000'],
            'original_ips.*.image_url' => ['nullable', 'url:http,https', 'max:2048'],
            'original_ips.*.photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'original_ips.*.url' => ['nullable', 'url:http,https', 'max:2048'],
            'original_ips.*.slug' => ['nullable', 'string', 'max:180', 'regex:/^[a-z0-9-]+$/', 'distinct'],
            'original_ips.*.details' => ['nullable', 'string', 'max:20000'],
            'original_ips.*.gallery_urls' => ['bail', 'nullable', 'string', 'max:65000', new MediaUrlList],
            'original_ips.*.video_urls' => ['bail', 'nullable', 'string', 'max:45000', new MediaUrlList(youtube: true)],
            'original_ips.*.photos' => ['nullable', 'array', 'max:20'],
            'original_ips.*.photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'team_members' => ['nullable', 'array', 'max:100'],
            'team_members.*' => ['array:name,role,image_url,photo'],
            'team_members.*.name' => ['required', 'string', 'max:255'],
            'team_members.*.role' => ['required', 'string', 'max:255'],
            'team_members.*.image_url' => ['nullable', 'url:http,https', 'max:2048'],
            'team_members.*.photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'products' => ['nullable', 'array', 'max:100'],
            'products.*' => ['array:name,description,url'],
            'products.*.name' => ['required', 'string', 'max:255'],
            'products.*.description' => ['nullable', 'string', 'max:1000'],
            'products.*.url' => ['required', 'url:http,https', 'max:2048'],
        ]);

        unset($data['logo'], $data['hero_image'], $data['remove_hero_image']);

        if (isset($data['original_ips'])) {
            foreach ($data['original_ips'] as $index => $ip) {
                $photoCount = count(array_filter(array_map('trim', preg_split('/\R/', $ip['gallery_urls'] ?? ''))));
                if ($photoCount + count($ip['photos'] ?? []) > 100) {
                    throw ValidationException::withMessages(["original_ips.$index.gallery_urls" => 'Maksimal 100 foto per IP, termasuk unggahan baru.']);
                }
            }

            foreach ($data['original_ips'] as &$ip) {
                $gallery = array_values(array_filter(array_map('trim', preg_split('/\R/', $ip['gallery_urls'] ?? ''))));

                foreach ($ip['photos'] ?? [] as $photo) {
                    $gallery[] = asset('storage/'.$photo->store('original-ips', 'public'));
                }

                $ip['gallery_urls'] = implode("\n", $gallery);
                unset($ip['photos']);
            }
            unset($ip);

            $data['original_ips'] = SiteSetting::originalIpsWithSlugs($data['original_ips']);
        }

        foreach (['clients' => 'clients', 'original_ips' => 'original-ips', 'team_members' => 'team-members'] as $section => $folder) {
            if (! isset($data[$section])) {
                continue;
            }

            foreach ($data[$section] as &$item) {
                if (isset($item['photo'])) {
                    $item['image_url'] = asset('storage/'.$item['photo']->store($folder, 'public'));
                }
                unset($item['photo']);
            }
            unset($item);
        }

        foreach (['clients', 'original_ips', 'team_members', 'products'] as $section) {
            if (array_key_exists($section, $data)) {
                $data[$section] = array_values($data[$section] ?? []);
            }
        }

        if ($request->boolean('remove_hero_image')) {
            $data['hero_image_path'] = null;
        }

        if ($request->hasFile('hero_image')) {
            $data['hero_image_path'] = $request->file('hero_image')->store('hero', 'public');
        }

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('logo', 'public');
        }

        SiteSetting::current()->update($data);

        return back()->with('status', 'Pengaturan berhasil disimpan.');
    }
}
