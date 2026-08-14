<?php

use App\Models\Portfolio;

test('it extracts youtube ids from supported url formats', function (string $url, string $videoId) {
    expect(Portfolio::youtubeIdFromUrl($url))->toBe($videoId);
})->with([
    'shorts' => ['https://youtube.com/shorts/z2DBxUUDWKc?feature=share', 'z2DBxUUDWKc'],
    'watch' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=10', 'dQw4w9WgXcQ'],
    'short link' => ['https://youtu.be/dQw4w9WgXcQ?si=example', 'dQw4w9WgXcQ'],
    'embed' => ['https://www.youtube.com/embed/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    'live' => ['https://www.youtube.com/live/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    'mobile' => ['https://m.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
]);

test('it rejects unsupported or malformed video urls', function (?string $url) {
    expect(Portfolio::youtubeIdFromUrl($url))->toBeNull();
})->with([
    'empty' => [null],
    'not youtube' => ['https://example.com/watch?v=dQw4w9WgXcQ'],
    'invalid id' => ['https://youtube.com/shorts/invalid'],
]);

test('it builds thumbnails and embeds for youtube shorts', function () {
    $portfolio = new Portfolio([
        'type' => 'video',
        'youtube_url' => 'https://youtube.com/shorts/z2DBxUUDWKc?feature=share',
    ]);

    expect($portfolio->youtube_id)->toBe('z2DBxUUDWKc')
        ->and($portfolio->youtube_thumbnail)->toBe('https://img.youtube.com/vi/z2DBxUUDWKc/hqdefault.jpg')
        ->and($portfolio->youtube_embed)->toBe('https://www.youtube.com/embed/z2DBxUUDWKc');
});
