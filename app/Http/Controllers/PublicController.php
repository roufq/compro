<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class PublicController extends Controller
{
    public function originalIp(string $slug): View
    {
        $settings = SiteSetting::current();
        $ip = collect($settings->originalIpItems())->firstWhere('slug', $slug);
        abort_if($ip === null, 404);

        $photos = preg_split('/\R/', $ip['gallery_urls'] ?? '', flags: PREG_SPLIT_NO_EMPTY);
        $videos = collect(preg_split('/\R/', $ip['video_urls'] ?? '', flags: PREG_SPLIT_NO_EMPTY))
            ->map(fn (string $url): ?string => Portfolio::youtubeIdFromUrl(trim($url)))
            ->filter()->values();

        return view('original-ip', compact('settings', 'ip', 'photos', 'videos'));
    }

    public function index(): View
    {
        $portfolios = Portfolio::query()->orderBy('order')->latest('id')->get();

        return view('welcome', [
            'settings' => SiteSetting::current(),
            'services' => Service::query()->orderBy('order')->orderBy('id')->get(),
            'portfolios' => $portfolios,
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
