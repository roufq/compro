<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OriginalIp;
use App\Rules\MediaUrlList;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OriginalIpController extends Controller
{
    public function index(): View
    {
        return view('admin.original-ips.index', [
            'originalIps' => OriginalIp::query()->orderBy('order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = OriginalIp::uniqueSlugFor($data['name']);

        OriginalIp::create($data);

        return back()->with('status', 'Original IP berhasil ditambahkan.');
    }

    public function update(Request $request, OriginalIp $originalIp): RedirectResponse
    {
        $data = $this->validated($request, $originalIp);

        $originalIp->update($data);

        return back()->with('status', 'Original IP berhasil diperbarui.');
    }

    public function destroy(OriginalIp $originalIp): RedirectResponse
    {
        $originalIp->delete();

        return back()->with('status', 'Original IP berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?OriginalIp $originalIp = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_url' => ['nullable', 'url:http,https', 'max:2048'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'url' => ['nullable', 'url:http,https', 'max:2048'],
            'details' => ['nullable', 'string', 'max:20000'],
            'gallery_urls' => ['bail', 'nullable', 'string', 'max:65000', new MediaUrlList],
            'photos' => ['nullable', 'array', 'max:20'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'video_urls' => ['bail', 'nullable', 'string', 'max:45000', new MediaUrlList(youtube: true)],
            'order' => ['sometimes', 'integer', 'min:0'],
        ]);

        $gallery = array_values(array_filter(array_map('trim', preg_split('/\R/', $data['gallery_urls'] ?? '') ?: [])));

        if (count($gallery) + count($data['photos'] ?? []) > 100) {
            throw ValidationException::withMessages(['gallery_urls' => 'Maksimal 100 foto per IP, termasuk unggahan baru.']);
        }

        foreach ($data['photos'] ?? [] as $photo) {
            $gallery[] = asset('storage/'.$photo->store('original-ips', 'public'));
        }
        $data['gallery_urls'] = implode("\n", $gallery);
        unset($data['photos']);

        if ($request->hasFile('cover')) {
            $data['image_url'] = asset('storage/'.$request->file('cover')->store('original-ips', 'public'));
        }
        unset($data['cover']);

        return $data;
    }
}
