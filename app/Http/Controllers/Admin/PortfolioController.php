<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('admin.portfolios.index', [
            'portfolios' => Portfolio::query()->orderBy('order')->latest('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        unset($data['image']);
        $data['type'] = 'video';

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('portfolio', 'public');
        }

        Portfolio::create($data);

        return back()->with('status', 'Karya berhasil ditambahkan.');
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $data = $this->validated($request);
        unset($data['image']);
        $data['type'] = 'video';

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('portfolio', 'public');
        }

        $portfolio->update($data);

        return back()->with('status', 'Karya berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $portfolio->delete();

        return back()->with('status', 'Karya berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:250',
            ],
            'youtube_url' => [
                'required',
                'url',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! is_string($value) || ! Portfolio::youtubeIdFromUrl($value)) {
                        $fail('Link video harus berupa URL YouTube yang valid.');
                    }
                },
            ],
            'order' => ['sometimes', 'integer', 'min:0'],
        ], [
            'image.max' => 'Ukuran thumbnail portofolio maksimal 250 KB.',
        ]);
    }
}
