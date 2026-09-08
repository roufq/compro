<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\OriginalIp;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class PublicController extends Controller
{
    public function originalIp(string $slug): View
    {
        $settings = SiteSetting::current();
        $ip = OriginalIp::query()->where('slug', $slug)->first();
        abort_if($ip === null, 404);

        $photos = $ip->photos;
        $videos = $ip->video_ids;

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
            'clients' => Client::query()->orderBy('order')->orderBy('id')->get(),
            'originalIps' => OriginalIp::query()->orderBy('order')->orderBy('id')->get(),
            'teamMembers' => TeamMember::query()->orderBy('order')->orderBy('id')->get(),
            'products' => Product::query()->orderBy('order')->orderBy('id')->get(),
        ]);
    }
}
