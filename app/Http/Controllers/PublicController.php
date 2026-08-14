<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class PublicController extends Controller
{
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
