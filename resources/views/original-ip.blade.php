<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $ip['name'] }} — {{ $settings->company_name }}</title>
  <meta name="description" content="{{ $ip['description'] ?? '' }}">
  <meta name="theme-color" content="#0D47A1">
  @if ($settings->logo_url)<link rel="icon" href="{{ $settings->logo_url }}">@endif
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--blue:#0D47A1;--deep:#082E6B;--accent:#FFD600;--panel:#F5F8FF;--line:rgba(13,71,161,.14);--text:#0B1B33;--muted:#5A6785;}
    *{box-sizing:border-box;}body{margin:0;background:#fff;color:var(--text);font:15px/1.8 'Poppins',sans-serif;}
    a{color:inherit;text-decoration:none;}img{display:block;max-width:100%;}h1,h2,h3{font-family:'Baloo 2',sans-serif;line-height:1.2;}
    .wrap{width:min(1136px,calc(100% - 48px));margin-inline:auto;}
    header{border-bottom:1px solid var(--line);background:#fff;}nav{display:flex;justify-content:space-between;align-items:center;gap:24px;padding-block:20px;}
    .brand{display:flex;align-items:center;gap:12px;font-weight:700;color:var(--blue);}.brand img{width:40px;height:40px;object-fit:contain;}
    .back{font-size:13px;font-weight:600;color:var(--blue);}.back:hover{text-decoration:underline;}
    .hero{padding:60px 0;background:var(--panel);}.breadcrumb{display:flex;flex-wrap:wrap;gap:10px;color:var(--muted);font-size:12px;margin-bottom:32px;}
    .hero-grid{display:grid;grid-template-columns:1.2fr 1fr;gap:56px;align-items:center;}
    .tag{display:inline-block;background:var(--accent);color:var(--deep);border-radius:100px;padding:5px 15px;font-size:12px;font-weight:700;}
    h1{font-size:clamp(36px,6vw,66px);color:var(--blue);margin:18px 0;}h2{font-size:32px;margin:0 0 24px;}p{color:var(--muted);}
    .cover{width:100%;max-height:420px;object-fit:contain;border-radius:24px;background:#fff;padding:24px;}
    .cover-placeholder{display:flex;align-items:center;justify-content:center;min-height:280px;padding:32px;background:linear-gradient(140deg,var(--blue),var(--deep));color:var(--accent);border-radius:24px;font:800 38px/1.2 'Baloo 2',sans-serif;text-align:center;}
    .section{padding-block:60px;}.section + .section{border-top:1px solid var(--line);}.copy{white-space:pre-line;overflow-wrap:anywhere;max-width:850px;color:var(--muted);}
    .gallery{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px;}.gallery figure{margin:0;}.gallery img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:16px;background:var(--panel);}
    .gallery a{display:block;border-radius:16px;overflow:hidden;}.gallery a:hover img{opacity:.9;}figcaption{font-size:12px;color:var(--muted);padding-top:8px;}
    .videos{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:24px;}.videos iframe{display:block;width:100%;aspect-ratio:16/9;border:0;border-radius:16px;background:var(--panel);}
    .video-preview{display:grid;width:100%;aspect-ratio:16/9;place-items:center;border:0;border-radius:16px;overflow:hidden;position:relative;background:var(--deep);color:white;cursor:pointer;font:600 15px 'Poppins',sans-serif;}
    .video-preview img{position:absolute;width:100%;height:100%;object-fit:cover;opacity:.65;}.video-preview span{position:relative;background:var(--blue);padding:12px 20px;border-radius:100px;}
    .button{display:inline-block;background:var(--blue);color:#fff;border-radius:100px;padding:12px 24px;font-size:13px;font-weight:600;margin-top:20px;}.button:hover{background:var(--deep);}
    footer{padding-block:30px;background:#0A0E1A;color:#fff;}footer .wrap{display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;font-size:12px;}
    :focus-visible{outline:3px solid var(--blue);outline-offset:5px;}
    @media(max-width:760px){.hero-grid{grid-template-columns:1fr;gap:28px;}.hero,.section{padding-block:36px;}.gallery{grid-template-columns:repeat(2,minmax(0,1fr));}.videos{grid-template-columns:1fr;}nav{align-items:flex-start;}.back{max-width:120px;text-align:right;}.cover-placeholder{min-height:200px;}}
    @media(max-width:420px){.gallery{grid-template-columns:1fr;}.wrap{width:calc(100% - 32px);}}
  </style>
</head>
<body>
  <header><nav class="wrap" aria-label="Navigasi utama">
    <a class="brand" href="{{ route('home') }}">@if ($settings->logo_url)<img src="{{ $settings->logo_url }}" alt="">@endif{{ $settings->company_name }}</a>
    <a class="back" href="{{ route('home') }}#our-ip">← Semua Original IP</a>
  </nav></header>
  <main>
    <section class="hero">
      <div class="wrap">
        <div class="breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>/</span><a href="{{ route('home') }}#our-ip">Original IP</a><span>/</span><span>{{ $ip['name'] }}</span></div>
        <div class="hero-grid">
          <div><span class="tag">Original IP</span><h1>{{ $ip['name'] }}</h1><p>{{ $ip['description'] ?? '' }}</p>
            @if (!empty($ip['url']))<a class="button" href="{{ $ip['url'] }}" target="_blank" rel="noopener noreferrer">Jelajahi {{ $ip['name'] }} ↗</a>@endif
          </div>
          @if (!empty($ip['image_url']))<img class="cover" src="{{ $ip['image_url'] }}" alt="{{ $ip['name'] }}">@else<div class="cover-placeholder">{{ $ip['name'] }}</div>@endif
        </div>
      </div>
    </section>
    <div class="wrap">
      <section class="section" aria-labelledby="about-ip"><h2 id="about-ip">Tentang {{ $ip['name'] }}</h2>
        <div class="copy">{{ $ip['details'] ?? 'Informasi lengkap akan segera tersedia.' }}</div>
      </section>
      @if (count($photos))
        <section class="section" aria-labelledby="gallery-heading"><h2 id="gallery-heading">Galeri Foto</h2>
          <div class="gallery">@foreach ($photos as $photo)
            <figure><a href="{{ trim($photo) }}" target="_blank" rel="noopener noreferrer" aria-label="Buka foto {{ $loop->iteration }} {{ $ip['name'] }} ukuran penuh"><img src="{{ trim($photo) }}" alt="{{ $ip['name'] }} — Foto {{ $loop->iteration }}" loading="lazy"></a><figcaption>{{ $ip['name'] }} · {{ $loop->iteration }}</figcaption></figure>
          @endforeach</div>
        </section>
      @endif
      @if ($videos->isNotEmpty())
        <section class="section" aria-labelledby="video-heading"><h2 id="video-heading">Video</h2>
          <div class="videos">@foreach ($videos as $video)
            <div>
              <button type="button" class="video-preview" data-video-src="https://www.youtube-nocookie.com/embed/{{ $video }}" aria-label="Putar {{ $ip['name'] }} — Video {{ $loop->iteration }}">
                <img src="https://img.youtube.com/vi/{{ $video }}/hqdefault.jpg" alt="" loading="lazy" decoding="async" width="480" height="360">
                <span>▶ Putar video</span>
              </button>
              <noscript><a href="https://www.youtube.com/watch?v={{ $video }}" target="_blank" rel="noopener noreferrer">Tonton di YouTube</a></noscript>
            </div>
          @endforeach</div>
        </section>
      @endif
      <section class="section"><a class="button" href="{{ route('home') }}#our-ip">← Kembali ke Original IP</a></section>
    </div>
  </main>
  <footer><div class="wrap"><span>© {{ date('Y') }} {{ $settings->company_name }}</span><span>{{ $settings->tagline }}</span></div></footer>
  <script>
    document.querySelectorAll('[data-video-src]').forEach(button => {
      button.addEventListener('click', () => {
        const frame = document.createElement('iframe');
        frame.src = button.dataset.videoSrc + '?autoplay=1';
        frame.title = button.getAttribute('aria-label');
        frame.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
        frame.allowFullscreen = true;
        frame.referrerPolicy = 'strict-origin-when-cross-origin';
        button.replaceWith(frame);
        frame.focus();
      }, { once: true });
    });
  </script>
</body>
</html>
