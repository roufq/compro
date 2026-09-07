<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function index(): View
    {
        $portfolios = Portfolio::query()->orderBy('order')->latest('id')->get();
        $portfolioCategories = $portfolios
            ->pluck('category')
            ->filter()
            ->reject(fn (string $category): bool => in_array(Str::lower(trim($category)), ['gambar', 'video'], true))
            ->unique(fn (string $category): string => Str::lower(trim($category)))
            ->values();

        return view('welcome', [
            'settings' => SiteSetting::current(),
            'services' => Service::query()->orderBy('order')->orderBy('id')->get(),
            'portfolios' => $portfolios,
            'portfolioCategories' => $portfolioCategories,
            'portfolioItems' => $portfolios->map(fn (Portfolio $portfolio): array => [
                'title' => $portfolio->title,
                'desc' => $portfolio->description,
                'category' => $portfolio->category,
                'type' => $portfolio->type,
                'thumb' => $portfolio->thumbnail_url,
                'embed' => $portfolio->youtube_embed,
            ]),
            'testimonials' => Testimonial::all(),
        ]);
    }
}
