<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $settings->company_name }} — {{ $settings->tagline }}</title>
<meta name="description" content="{{ $settings->hero_description }}">
<meta name="theme-color" content="#0D47A1">
@if ($settings->logo_url)
<link rel="icon" href="{{ $settings->logo_url }}?v={{ $settings->updated_at?->timestamp ?? 1 }}" sizes="any">
<link rel="apple-touch-icon" href="{{ $settings->logo_url }}?v={{ $settings->updated_at?->timestamp ?? 1 }}">
@else
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 60'%3E%3Ccircle cx='30' cy='26' r='24' fill='%230D47A1'/%3E%3Cpath d='M14 34 L38 22 L38 40 Z' fill='%23FFD600'/%3E%3C/svg%3E">
@endif
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $settings->company_name }} — {{ $settings->tagline }}">
<meta property="og:description" content="{{ $settings->hero_description }}">
<meta name="twitter:card" content="summary_large_image">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --void:#FFFFFF;
    --panel:#F5F8FF;
    --panel-2:#EAF0FF;
    --line:rgba(13,71,161,0.14);
    --text-1:#0B1B33;
    --text-2:#5A6785;
    --text-3:#95A0BC;
    --blue:#0D47A1;
    --blue-deep:#082E6B;
    --accent:#FFD600;
    --black:#0A0E1A;
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  html{scroll-behavior:smooth;}
  body{background:var(--void);color:var(--text-1);font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;overflow-x:hidden;line-height:1.6;}
  h1,h2,h3{font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;font-weight:600;letter-spacing:-0.03em;}
  a{color:inherit;text-decoration:none;}
  .wrap{max-width:1200px;margin:0 auto;padding:0 32px;}
  ::selection{background:var(--accent);color:var(--black);}
  img{max-width:100%;display:block;}

  .tag{
    font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;font-weight:600;font-size:12px;color:var(--blue);
    text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;display:inline-block;
    background:var(--panel-2);padding:6px 14px;border-radius:100px;
  }

  .reveal.animate{opacity:0;transform:translateY(18px);transition:opacity .6s ease, transform .6s ease;}
  .reveal.animate.in{opacity:1;transform:translateY(0);}
  .reveal.d1{transition-delay:.08s;}
  .reveal.d2{transition-delay:.16s;}
  .reveal.d3{transition-delay:.24s;}

  /* ===== NAVBAR ===== */
  header{position:sticky;top:0;z-index:50;background:rgba(255,255,255,0.88);backdrop-filter:blur(10px);border-bottom:1px solid var(--line);}
  nav{display:flex;align-items:center;justify-content:space-between;padding:14px 32px;max-width:1200px;margin:0 auto;}
  .logo{display:flex;align-items:center;gap:10px;}
  .logo svg{width:36px;height:36px;flex-shrink:0;}
  .logo-text{display:flex;flex-direction:column;line-height:1;}
  .logo-text .kd{font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;font-weight:700;letter-spacing:-0.02em;font-size:17px;color:var(--text-1);}
  .logo-text .st{font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;font-weight:600;font-size:8.5px;letter-spacing:0.22em;color:var(--text-2);}
  .nav-links{display:flex;gap:28px;font-size:14px;font-weight:600;color:var(--text-2);}
  .nav-links a{position:relative;padding-bottom:3px;}
  .nav-links a::after{content:'';position:absolute;left:0;bottom:0;width:0;height:2px;background:var(--accent);transition:width .25s ease;}
  .nav-links a:hover{color:var(--blue);}
  .nav-links a:hover::after{width:100%;}
  .nav-cta{padding:10px 20px;border-radius:100px;font-size:13px;font-weight:700;background:var(--blue);color:#fff;}
  .burger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;}
  .burger span{width:22px;height:2.5px;background:var(--blue);display:block;border-radius:2px;}
  .mobile-menu{display:none;position:absolute;top:100%;left:0;right:0;height:calc(100dvh - 76px);overflow-y:auto;z-index:49;background:#fff;padding:32px;flex-direction:column;gap:22px;font-size:18px;font-weight:600;}
  .mobile-menu.open{display:flex;}

  section{padding:88px 0;position:relative;}
  .alt-bg{background:var(--panel);}
  .sec-head{max-width:640px;margin:0 auto 40px;text-align:center;}
  .sec-head h2{font-size:clamp(24px,3.4vw,34px);margin-bottom:10px;}
  .sec-head p{color:var(--text-2);font-size:15px;}

  /* ===== HERO VISUAL ===== */
  .hero{padding:44px 0 0;}
  .hero-visual{
    max-width:980px;margin:0 auto;border-radius:24px;overflow:hidden;position:relative;
    aspect-ratio:16/8.2;box-shadow:0 30px 70px -24px rgba(13,71,161,0.4);
    background:
      radial-gradient(circle at 50% 46%, #FFF7D6 0%, #FFE9A0 8%, #FFD54F 16%, rgba(255,213,79,0.25) 26%, transparent 40%),
      linear-gradient(180deg, #050B1E 0%, #0A1B45 40%, #123B7A 68%, #1857C4 100%);
  }
  .hero-stars{position:absolute;inset:0;background-image:
      radial-gradient(1.5px 1.5px at 20px 30px, #fff, transparent),
      radial-gradient(1.5px 1.5px at 90px 80px, #fff, transparent),
      radial-gradient(1px 1px at 160px 40px, #fff, transparent),
      radial-gradient(1.5px 1.5px at 230px 100px, #fff, transparent),
      radial-gradient(1px 1px at 300px 20px, #fff, transparent),
      radial-gradient(1.5px 1.5px at 380px 70px, #fff, transparent),
      radial-gradient(1px 1px at 60px 120px, #fff, transparent),
      radial-gradient(1.5px 1.5px at 450px 50px, #fff, transparent);
    background-repeat:repeat-x;background-size:500px 160px;opacity:0.8;
  }
  .hero-boat{position:absolute;left:50%;top:44%;transform:translate(-50%,-50%);width:11%;z-index:2;filter:drop-shadow(0 6px 10px rgba(0,0,0,0.35));animation:bobBoat 4s ease-in-out infinite;}
  @keyframes bobBoat{0%,100%{transform:translate(-50%,-50%) translateY(0);}50%{transform:translate(-50%,-50%) translateY(-6px);}}
  .hero-water{position:absolute;left:0;right:0;bottom:0;height:34%;
    background:linear-gradient(180deg, rgba(24,87,196,0.2), rgba(5,11,30,0.9));
  }
  .hero-water::before{
    content:'';position:absolute;inset:0;
    background-image:repeating-linear-gradient(90deg, rgba(255,255,255,0.06) 0 2px, transparent 2px 40px);
    animation:waterMove 6s linear infinite;
  }
  @keyframes waterMove{from{background-position:0 0;}to{background-position:-200px 0;}}
  .hero-glow-line{position:absolute;left:50%;bottom:0;top:46%;width:6%;transform:translateX(-50%);
    background:linear-gradient(180deg, rgba(255,229,143,0.55), rgba(255,229,143,0));
    filter:blur(2px);z-index:1;
  }
  .hero-badge{
    position:absolute;bottom:18px;left:18px;z-index:3;display:flex;align-items:center;gap:8px;
    background:rgba(5,11,30,0.55);color:#fff;padding:8px 16px;border-radius:100px;
    font-size:12px;font-weight:700;letter-spacing:0.04em;
  }
  .hero-badge .dot{width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulseA 1.6s infinite;}
  @keyframes pulseA{0%,100%{opacity:1;transform:scale(1);}50%{opacity:0.4;transform:scale(1.3);}}
  .hero-caption{text-align:center;margin-top:18px;font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;font-weight:600;color:var(--blue);font-size:15px;letter-spacing:0.01em;}

  /* ===== BEYOND THE IMAGINATION ===== */
  .imagine-grid{display:grid;grid-template-columns:0.8fr 1.2fr;gap:56px;align-items:center;}
  .imagine-grid h2{font-size:clamp(28px,3.6vw,40px);line-height:1.15;color:var(--blue);}
  .imagine-grid p{color:var(--text-2);font-size:15.5px;}

  /* ===== TRUSTED BY ===== */
  .trusted-marquee{
    overflow:hidden;position:relative;
    -webkit-mask-image:linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent);
    mask-image:linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent);
  }
  .trusted-track{display:flex;align-items:center;gap:56px;width:max-content;animation:trusted-scroll 30s linear infinite;}
  .trusted-marquee:hover .trusted-track{animation-play-state:paused;}
  .trusted-logo{display:flex;align-items:center;justify-content:center;flex-shrink:0;filter:none;opacity:1;}
  .trusted-logo-text{
    font-family:-apple-system,BlinkMacSystemFont,'SF Pro Display','Segoe UI',Helvetica,Arial,sans-serif;
    font-weight:600;font-size:15px;letter-spacing:-0.01em;color:var(--text-3);white-space:nowrap;
  }
  @keyframes trusted-scroll{from{transform:translateX(0);}to{transform:translateX(-50%);}}

  /* ===== PORTFOLIO ===== */
  .filter-bar{display:flex;gap:10px;justify-content:center;margin-bottom:36px;flex-wrap:wrap;}
  .filter-btn{font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;font-weight:600;font-size:13px;padding:9px 18px;border-radius:100px;border:1px solid var(--line);color:var(--text-2);background:transparent;cursor:pointer;transition:.2s;}
  .filter-btn.active{background:var(--blue);color:#fff;border-color:var(--blue);}
  .filter-btn:hover:not(.active){border-color:var(--blue);color:var(--blue);}
  .portfolio-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
  .p-item{aspect-ratio:16/10;border-radius:12px;overflow:hidden;position:relative;cursor:pointer;border:1px solid var(--line);}
  .p-item .fill{width:100%;height:100%;transition:transform .4s ease;display:flex;align-items:flex-end;padding:12px;}
  .p-item:hover .fill{transform:scale(1.05);}
  .p-item .badge-name{font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;font-weight:600;letter-spacing:-0.01em;font-size:18px;color:#fff;text-shadow:0 2px 6px rgba(0,0,0,0.4);}
  .p-item .overlay{position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(8,20,40,0.85));display:flex;flex-direction:column;justify-content:flex-end;padding:14px;opacity:0;transition:.2s;}
  .p-item:hover .overlay{opacity:1;}
  .p-item .cat{font-size:10px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;}
  .p-item .ttl{font-size:13px;font-weight:600;color:#fff;}
  .load-more{display:block;margin:36px auto 0;}
  .btn-ghost{padding:12px 26px;border-radius:100px;border:1.5px solid var(--blue);color:var(--blue);font-weight:700;font-size:14px;transition:.2s;background:transparent;cursor:pointer;}
  .btn-ghost:hover{background:var(--blue);color:#fff;}

  /* ===== OUR IP ===== */
  .ip-grid{display:flex;justify-content:center;align-items:center;gap:60px;flex-wrap:wrap;}
  .ip-card{display:flex;flex-direction:column;align-items:center;gap:10px;transition:transform .3s ease;}
  .ip-card:hover{transform:translateY(-4px);}
  .ip-card--disabled{cursor:default;opacity:0.6;}
  .ip-card--disabled:hover{transform:none;}
  .ip-logo{font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;font-weight:700;font-size:24px;letter-spacing:-0.02em;color:var(--text-1);}
  .ip-logo.jas{font-weight:600;font-size:18px;color:var(--text-2);border:1.5px solid var(--line);padding:10px 18px;border-radius:10px;}
  .ip-card span{
    font-family:-apple-system,BlinkMacSystemFont,'SF Pro Display','Segoe UI',Helvetica,Arial,sans-serif;
    font-size:13px;letter-spacing:-0.01em;color:var(--text-3);
  }
  .ip-card .ip-cta{font-weight:600;color:var(--blue);display:inline-flex;align-items:center;gap:4px;transition:gap .2s;}
  .ip-card:hover .ip-cta{gap:7px;}

  /* ===== TEAM ===== */
  .team-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px;}
  .team-item{text-align:center;}
  .team-avatar{
    aspect-ratio:1/1;border-radius:14px;margin-bottom:10px;display:flex;align-items:center;justify-content:center;
    font-weight:700;font-size:22px;color:#fff;
  }
  .team-item h4{font-size:13.5px;margin-bottom:2px;}
  .team-item span{font-size:11.5px;color:var(--text-3);}
  .team-note{text-align:center;font-size:12px;color:var(--text-3);margin-top:22px;font-style:italic;}

  /* ===== CONTACT ===== */
  .contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:stretch;}
  .contact-info{display:flex;flex-direction:column;gap:20px;}
  .info-row{display:flex;gap:14px;align-items:flex-start;}
  .info-row .ic{width:40px;height:40px;border-radius:10px;background:var(--panel-2);color:var(--blue);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;}
  .info-row b{display:block;font-size:14px;margin-bottom:2px;}
  .info-row span{font-size:13.5px;color:var(--text-2);}
  .map-frame{border-radius:16px;overflow:hidden;border:1px solid var(--line);min-height:200px;background:var(--panel);display:flex;align-items:center;justify-content:center;}
  .map-frame iframe{width:100%;height:100%;border:0;min-height:200px;}
  .contact-buttons{display:flex;gap:14px;flex-wrap:wrap;margin-top:6px;}
  .contact-btn{display:flex;align-items:center;gap:10px;padding:12px 18px;border-radius:12px;border:1px solid var(--line);background:#fff;font-size:13.5px;font-weight:600;transition:.2s;}
  .contact-btn:hover{border-color:var(--blue);transform:translateY(-2px);}
  .contact-btn .ic2{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;}
  .contact-btn.wa .ic2{background:#e7f9ef;color:#25D366;}
  .contact-btn.maps .ic2{background:#eaf0ff;color:var(--blue);}
  .contact-btn.ig .ic2{background:#fdeef6;color:#E1306C;}

  /* ===== DOWNLOAD ===== */
  .download-section{background:var(--blue);color:#fff;}
  .download-section .sec-head p{color:rgba(255,255,255,0.75);}
  .download-section .tag{background:rgba(255,255,255,0.14);color:var(--accent);}
  .download-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;}
  .download-card{background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.16);border-radius:16px;padding:26px;display:flex;flex-direction:column;gap:14px;transition:.2s;}
  .download-card:hover{background:rgba(255,255,255,0.12);transform:translateY(-4px);}
  .download-card .dic{width:40px;height:40px;border-radius:10px;background:var(--accent);color:var(--black);display:flex;align-items:center;justify-content:center;font-size:16px;}
  .download-card h3{font-size:16px;}
  .download-card p{font-size:13px;color:rgba(255,255,255,0.7);flex-grow:1;}
  .download-card .dl-btn{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:var(--accent);}
  .download-card .dl-btn:hover{text-decoration:underline;}

  /* ===== LIGHTBOX ===== */
  .lightbox{position:fixed;inset:0;z-index:100;background:rgba(8,14,26,0.92);backdrop-filter:blur(10px);display:none;align-items:center;justify-content:center;padding:40px;}
  .lightbox.open{display:flex;}
  .lb-content{max-width:800px;width:100%;text-align:center;}
  .lb-visual{width:100%;aspect-ratio:16/10;border-radius:16px;margin-bottom:20px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,0.14);}
  .lb-close{position:absolute;top:28px;right:32px;font-size:28px;color:rgba(255,255,255,0.7);cursor:pointer;background:none;border:none;}
  .lb-nav{position:absolute;top:50%;transform:translateY(-50%);font-size:28px;color:rgba(255,255,255,0.7);cursor:pointer;background:none;border:none;padding:10px;}
  .lb-prev{left:20px;}.lb-next{right:20px;}
  .lb-close:hover,.lb-nav:hover{color:#fff;}
  .lb-cat{font-weight:700;font-size:12px;color:var(--accent);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;}
  .lb-title{font-size:20px;color:#fff;}

  /* ===== FOOTER ===== */
  footer{background:var(--black);color:rgba(255,255,255,0.7);padding:56px 0 26px;}
  .foot-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:40px;margin-bottom:40px;}
  .foot-logo{display:flex;align-items:center;gap:10px;margin-bottom:14px;}
  .foot-logo .kd{font-weight:700;letter-spacing:-0.02em;color:#fff;font-size:18px;}
  .foot-logo .st{font-weight:600;font-size:10px;letter-spacing:0.2em;color:rgba(255,255,255,0.55);}
  .foot-grid p{font-size:14px;max-width:300px;color:rgba(255,255,255,0.55);}
  .foot-grid h4{font-size:12px;color:rgba(255,255,255,0.4);margin-bottom:14px;text-transform:uppercase;letter-spacing:0.08em;font-weight:700;}
  .foot-grid ul{list-style:none;}
  .foot-grid li{margin-bottom:10px;font-size:14px;}
  .foot-grid li a:hover{color:var(--accent);}
  .foot-bottom{display:flex;justify-content:space-between;align-items:center;font-size:12px;color:rgba(255,255,255,0.4);flex-wrap:wrap;gap:12px;border-top:1px solid rgba(255,255,255,0.1);padding-top:22px;}

  /* ===== RESPONSIVE ===== */
  @media(max-width:1024px){
    .portfolio-grid{grid-template-columns:repeat(2,1fr);}
    .team-grid{grid-template-columns:repeat(3,1fr);}
    .download-grid{grid-template-columns:repeat(2,1fr);}
    .imagine-grid{grid-template-columns:1fr;gap:24px;text-align:center;}
  }
  @media(max-width:860px){
    .nav-links{display:none;}
    .burger{display:flex;}
    .foot-grid{grid-template-columns:1fr 1fr;row-gap:28px;}
    .contact-grid{grid-template-columns:1fr;}
    .map-frame,.map-frame iframe{min-height:280px;}
  }
  @media(max-width:640px){
    .wrap{padding:0 20px;}
    nav{padding:12px 20px;}
    section{padding:60px 0;}
    .hero-visual{border-radius:16px;}
    .portfolio-grid{grid-template-columns:1fr 1fr;}
    .team-grid{grid-template-columns:repeat(2,1fr);}
    .download-grid{grid-template-columns:1fr;}
    .foot-grid{grid-template-columns:1fr;}
    .ip-grid{gap:30px;}
  }
  @media(prefers-reduced-motion:reduce){
    *:not(.trusted-track){animation-duration:0.01ms !important;animation-iteration-count:1 !important;transition-duration:0.01ms !important;}
    .reveal{opacity:1;transform:none;}
  }
  [hidden]{display:none!important;}
  section{scroll-margin-top:76px;}
  .hero-image{width:100%;height:100%;object-fit:cover;}
  .hero-video{width:100%;height:100%;border:0;object-fit:cover;pointer-events:none;}
  .hero-caption{white-space:pre-line;}
  h1.hero-caption{font-size:clamp(24px,4vw,40px);}
  .logo-text{max-width:230px;gap:4px;}.logo-text .st{letter-spacing:.03em;line-height:1.3;}
  .p-item .fill{background:linear-gradient(150deg,#1857C4,#082E6B);position:relative;}
  .p-item .fill img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;}
  .p-item .badge-name{position:relative;text-align:left;}
  .p-item:focus-visible .overlay{opacity:1;}
  .lb-visual{overflow:hidden;}.lb-visual img,.lb-visual iframe{width:100%;height:100%;object-fit:contain;border:0;}
  .trusted-logo img{max-width:130px;max-height:60px;object-fit:contain;}
  .ip-card img{max-width:200px;max-height:100px;object-fit:contain;}
  .team-avatar{background:var(--blue);overflow:hidden;}.team-avatar img{width:100%;height:100%;object-fit:cover;}
  .legacy-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px;}
  .legacy-card{border:1px solid var(--line);border-radius:16px;padding:24px;}
  @media(max-width:640px){.logo-text{max-width:155px;}.logo-text .kd{font-size:15px;}.nav-cta{padding:8px 12px;font-size:11px;}.lightbox{padding:50px 35px;}.lb-prev{left:0;}.lb-next{right:0;}}
  @media(prefers-reduced-motion:reduce){html{scroll-behavior:auto;}.reveal.animate{opacity:1;transform:none;}}
</style>
</head>
<body>
@php
    $phoneDigits = preg_replace('/[^0-9]/', '', $settings->phone ?? '');
    $whatsappNumber = str_starts_with($phoneDigits, '0') ? '62'.substr($phoneDigits, 1) : $phoneDigits;
    $emailUrl = 'https://mail.google.com/mail/?view=cm&fs=1&to='.rawurlencode($settings->email ?? '');
@endphp

<header>
  <nav>
    <a href="#" class="logo">
      @if ($settings->logo_url)<img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }}" width="36" height="36">@else<svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
        <circle cx="30" cy="26" r="24" fill="#0D47A1"/>
        <path d="M14 34 L38 22 L38 40 Z" fill="#FFD600"/>
        <circle cx="20" cy="20" r="4" fill="#FFD600"/>
        <rect x="24" y="16" width="8" height="2.4" rx="1.2" fill="#FFD600"/>
      </svg>@endif
      <span class="logo-text"><span class="kd">{{ $settings->company_name }}</span><span class="st">{{ $settings->tagline }}</span></span>
    </a>
    <div class="nav-links">
      <a href="#about">About Us</a>
      <a href="#portfolio">Projects</a>
      <a href="#trusted">Clients</a>
      <a href="#our-ip">IPs</a>
      <a href="#contact">Contact Us</a>
    </div>
    <a href="#contact" class="nav-cta">Hubungi Kami</a>
    <button class="burger" id="burger" aria-label="Buka menu" aria-controls="mobileMenu" aria-expanded="false"><span></span><span></span><span></span></button>
  </nav>
  <div class="mobile-menu" id="mobileMenu">
    <a href="#about">About Us</a>
    <a href="#portfolio">Projects</a>
    <a href="#trusted">Clients</a>
    <a href="#our-ip">IPs</a>
    <a href="#contact">Contact Us</a>
  </div>
</header>

<main>
  <!-- HERO VISUAL -->
  <section class="hero">
    <div class="wrap">
      <div class="hero-visual reveal">
        @if ($settings->hero_video_embed)
        <iframe class="hero-video" src="{{ $settings->hero_video_embed }}" title="{{ $settings->hero_title }}" allow="autoplay; encrypted-media; picture-in-picture" loading="lazy"></iframe>
        @elseif ($settings->hero_image_url)<img class="hero-image" src="{{ $settings->hero_image_url }}" alt="{{ $settings->hero_title }}">@else
        <div class="hero-stars"></div>
        <div class="hero-glow-line"></div>
        <svg class="hero-boat" viewBox="0 0 120 130" xmlns="http://www.w3.org/2000/svg">
          <path d="M10 90 L60 55 L60 100 Z" fill="#0A1B45"/>
          <path d="M60 55 L60 100 L110 90 Z" fill="#123B7A"/>
          <circle cx="55" cy="48" r="7" fill="#050B1E"/>
          <rect x="58" y="42" width="16" height="4" rx="2" fill="#050B1E"/>
        </svg>
        <div class="hero-water"></div>
        @endif
        <div class="hero-badge"><span class="dot"></span>{{ $settings->company_name }}</div>
      </div>
      <h1 class="hero-caption reveal">{{ $settings->hero_title }}</h1>
      <p class="hero-caption reveal">{{ $settings->tagline }}</p>
    </div>
  </section>

  <!-- BEYOND THE IMAGINATION -->
  <section id="about">
    <div class="wrap imagine-grid">
      <h2 class="reveal">{{ $settings->about_title ?: "Beyond The Imagination" }}</h2>
      <p class="reveal d1">{{ $settings->about_description ?: $settings->hero_description }}</p>
    </div>
  </section>

  <!-- TRUSTED BY -->
  <section id="trusted" class="alt-bg">
    <div class="wrap">
      <div class="sec-head reveal"><span class="tag">Trusted By</span><h2>Dipercaya oleh</h2></div>
      @if ($clients->isNotEmpty())
        <div class="trusted-marquee reveal">
          <div class="trusted-track">
            @for ($rep = 0; $rep < 2; $rep++)
              @foreach ($clients as $client)
                <span class="trusted-logo" aria-hidden="{{ $rep === 1 ? 'true' : 'false' }}">
                  @if (!empty($client['image_url']))
                    <img src="{{ $client['image_url'] }}" alt="{{ $client['name'] }}" loading="lazy">
                  @else
                    <span class="trusted-logo-text">{{ $client['name'] }}</span>
                  @endif
                </span>
              @endforeach
            @endfor
          </div>
        </div>
      @else
        <p>Daftar klien akan segera ditampilkan.</p>
      @endif
    </div>
  </section>

  <!-- PORTFOLIO -->
  <section id="portfolio">
    <div class="wrap">
      <div class="sec-head reveal">
        <span class="tag">Portofolio</span>
        <h2>Karya yang sudah kami produksi</h2>
        <p>Sebagian hasil produksi commercial, animasi, dan original IP kami.</p>
      </div>
      <div class="filter-bar" id="filterBar">
        <button class="filter-btn active" data-f="all">Semua</button>
        @foreach ($portfolios->pluck('category')->unique() as $category)
          <button class="filter-btn" data-f="{{ $category }}">{{ $category === 'character' ? 'Karakter' : ucfirst($category) }}</button>
        @endforeach
      </div>
      <div class="portfolio-grid" id="portfolioGrid">
        @forelse ($portfolios as $portfolio)
          <button type="button" class="p-item" data-portfolio-index="{{ $loop->index }}" data-category="{{ $portfolio->category }}" aria-label="Lihat {{ $portfolio->title }}">
            <div class="fill">
              @if ($portfolio->thumbnail_url)<img src="{{ $portfolio->thumbnail_url }}" alt="{{ $portfolio->title }}" loading="lazy">@endif
              <span class="badge-name">{{ $portfolio->title }}</span>
            </div>
            <div class="overlay"><div class="cat">{{ $portfolio->category }}</div><div class="ttl">{{ $portfolio->title }}</div></div>
          </button>
        @empty
          <p>Portofolio akan segera ditampilkan.</p>
        @endforelse
      </div>
      <button class="btn-ghost load-more" id="loadMore">Muat Lebih Banyak</button>
    </div>
  </section>

  <!-- OUR IP -->
  <section id="our-ip" class="alt-bg">
    <div class="wrap">
      <div class="sec-head reveal"><span class="tag">Our IP</span><h2>Original IP kami</h2></div>
      <div class="ip-grid reveal">
        @forelse ($originalIps as $ip)
          @php($ipHasChannel = !empty($ip['url']))
          @php($ipTag = $ipHasChannel ? 'a' : 'div')
          <{{ $ipTag }} class="ip-card @if (! $ipHasChannel) ip-card--disabled @endif" @if ($ipHasChannel) href="{{ $ip['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="Tonton {{ $ip['name'] }} di YouTube" @endif>
            @if (!empty($ip['image_url']))<img src="{{ $ip['image_url'] }}" alt="{{ $ip['name'] }}" loading="lazy">@else<div class="ip-logo {{ $loop->index % 2 === 0 ? 'teman' : 'happy' }}">{{ $ip['name'] }}</div>@endif
            <span>{{ $ip['description'] ?? 'Original IP' }}</span>
            @if ($ipHasChannel)
              <span class="ip-cta">Tonton di YouTube ↗</span>
            @endif
          </{{ $ipTag }}>
        @empty
          <p>Original IP akan segera ditampilkan.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- OUR TEAM -->
  <section id="team">
    <div class="wrap">
      <div class="sec-head reveal"><span class="tag">Our Team</span><h2>Tim di balik karya</h2></div>
      <div class="team-grid" id="teamGrid">
        @forelse ($teamMembers as $member)
          <div class="team-item reveal">
            <div class="team-avatar">@if (!empty($member['image_url']))<img src="{{ $member['image_url'] }}" alt="{{ $member['name'] }}" loading="lazy">@else{{ mb_substr($member['name'], 0, 1) }}@endif</div>
            <h4>{{ $member['name'] }}</h4><span>{{ $member['role'] }}</span>
          </div>
        @empty
          <p>Profil tim akan segera ditampilkan.</p>
        @endforelse
      </div>
    </div>
  </section>

  @if ($services->isNotEmpty())
  <section id="services" class="alt-bg"><div class="wrap">
    <div class="sec-head"><span class="tag">Layanan</span><h2>Solusi produksi kreatif</h2></div>
    <div class="legacy-grid">@foreach ($services as $service)<article class="legacy-card"><h3>{{ $service->title }}</h3><p>{{ $service->description }}</p></article>@endforeach</div>
  </div></section>
  @endif
  @if ($testimonials->isNotEmpty())
  <section id="testimonials"><div class="wrap">
    <div class="sec-head"><span class="tag">Testimoni</span><h2>Cerita dari klien kami</h2></div>
    <div class="legacy-grid">@foreach ($testimonials as $testimonial)<figure class="legacy-card"><blockquote>{{ $testimonial->quote }}</blockquote><figcaption><strong>{{ $testimonial->name }}</strong><p>{{ $testimonial->role }}</p></figcaption></figure>@endforeach</div>
  </div></section>
  @endif
  <!-- CONTACT -->
  <section id="contact" class="alt-bg">
    <div class="wrap">
      <div class="sec-head reveal"><span class="tag">Contact Us</span><h2>Mari diskusikan proyek Anda</h2></div>
      <div class="contact-grid reveal">
        <div class="contact-info">
          <div class="info-row">
            <div class="ic">📍</div>
            <div><b>Alamat Kantor</b><span>{{ $settings->address ?: 'Alamat belum tersedia' }}</span></div>
          </div>
          <div class="info-row">
            <div class="ic">✉️</div>
            <div><b>Email</b><span>@if ($settings->email)<a href="{{ $emailUrl }}" target="_blank" rel="noopener noreferrer">{{ $settings->email }}</a>@else Email belum tersedia @endif</span></div>
          </div>
          <div class="info-row">
            <div class="ic">📞</div>
            <div><b>Nomor Admin</b><span>{{ $settings->phone ?: 'Nomor belum tersedia' }}</span></div>
          </div>
          <div class="contact-buttons">
            @if ($whatsappNumber)<a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener noreferrer" class="contact-btn wa"><div class="ic2">💬</div>WhatsApp</a>@endif
            @if ($settings->instagram_url)<a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" class="contact-btn ig"><div class="ic2">📷</div>Instagram</a>@endif
            @if ($settings->map_query ?: $settings->address)<a href="https://www.google.com/maps/search/?api=1&amp;query={{ rawurlencode($settings->map_query ?: $settings->address) }}" target="_blank" rel="noopener noreferrer" class="contact-btn maps"><div class="ic2">📍</div>Buka di Google Maps</a>@endif
          </div>
        </div>
        <div class="map-frame">
          @if ($settings->map_query ?: $settings->address)
          <iframe title="Lokasi kantor" src="https://www.google.com/maps?q={{ rawurlencode($settings->map_query ?: $settings->address) }}&amp;iwloc=&amp;z=16&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          @else<p>Lokasi kantor belum tersedia.</p>@endif
        </div>
      </div>
    </div>
  </section>

  <!-- DOWNLOAD (dipertahankan dari brief sebelumnya, ditaruh di akhir) -->
  <section id="download" class="download-section">
    <div class="wrap">
      <div class="sec-head reveal">
        <span class="tag">Download</span>
        <h2>Produk & aset kami</h2>
        <p>Dapatkan produk digital kami melalui ScaleV.</p>
      </div>
      <div class="download-grid" id="downloadGrid">
        @forelse ($products as $product)
          <div class="download-card reveal">
            <div class="dic">↓</div><h3>{{ $product['name'] }}</h3><p>{{ $product['description'] ?? '' }}</p>
            <a href="{{ $product['url'] }}" target="_blank" rel="noopener noreferrer" class="dl-btn">Lihat Produk →</a>
          </div>
        @empty
          <p>Produk digital akan segera tersedia.</p>
        @endforelse
      </div>
    </div>
  </section>
</main>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="foot-logo">
          @if ($settings->logo_url)<img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }}" width="34" height="34">@else<svg width="34" height="34" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
            <circle cx="30" cy="26" r="24" fill="#FFD600"/>
            <path d="M14 34 L38 22 L38 40 Z" fill="#0A0E1A"/>
          </svg>@endif
          <span class="kd">{{ $settings->company_name }}</span>
        </div>
        <p>{{ $settings->hero_description }}</p>
      </div>
      <div>
        <h4>Navigasi</h4>
        <ul>
          <li><a href="#about">About Us</a></li>
          <li><a href="#portfolio">Projects</a></li>
          <li><a href="#our-ip">IPs</a></li>
        </ul>
      </div>
      <div>
        <h4>Kontak</h4>
        <ul>
          <li>@if ($whatsappNumber)<a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>@endif</li>
          <li>@if ($settings->instagram_url)<a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer">Instagram</a>@endif</li>
          @if ($settings->email)<li><a href="{{ $emailUrl }}" target="_blank" rel="noopener noreferrer">Email</a></li>@endif
          @if ($settings->linkedin_url)<li><a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>@endif
          @if ($settings->youtube_url)<li><a href="{{ $settings->youtube_url }}" target="_blank" rel="noopener noreferrer">YouTube</a></li>@endif
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© {{ date('Y') }} {{ $settings->company_name }}. Seluruh hak cipta dilindungi.</span>
      <span>{{ $settings->tagline }}</span>
    </div>
  </div>
</footer>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-labelledby="lbTitle" tabindex="-1">
  <button class="lb-close" id="lbClose" aria-label="Tutup">×</button>
  <button class="lb-nav lb-prev" id="lbPrev" aria-label="Karya sebelumnya">‹</button>
  <button class="lb-nav lb-next" id="lbNext" aria-label="Karya berikutnya">›</button>
  <div class="lb-content">
    <div class="lb-visual" id="lbVisual"></div>
    <p id="lbDescription" style="color:white"></p>
    <div class="lb-cat" id="lbCat"></div>
    <h3 class="lb-title" id="lbTitle"></h3>
  </div>
</div>

<script>
const portfolioItems = {{ Illuminate\Support\Js::from($portfolioItems) }};
const portfolioButtons = [...document.querySelectorAll('[data-portfolio-index]')];
const loadMore = document.getElementById('loadMore');
let activeCategory = 'all';
let visibleCount = 9;
let filteredButtons = portfolioButtons;
function filterPortfolio() {
  filteredButtons = portfolioButtons.filter(button => activeCategory === 'all' || button.dataset.category === activeCategory);
  portfolioButtons.forEach(button => { button.hidden = true; });
  filteredButtons.slice(0, visibleCount).forEach(button => { button.hidden = false; });
  loadMore.hidden = filteredButtons.length <= visibleCount;
}
document.getElementById('filterBar').addEventListener('click', event => {
  const button = event.target.closest('.filter-btn');
  if (!button) return;
  document.querySelectorAll('.filter-btn').forEach(item => item.classList.toggle('active', item === button));
  activeCategory = button.dataset.f;
  visibleCount = 9;
  filterPortfolio();
});
loadMore.addEventListener('click', () => { visibleCount += 9; filterPortfolio(); });
filterPortfolio();
const lightbox = document.getElementById('lightbox');
const visual = document.getElementById('lbVisual');
let selectedIndex = 0;
let opener;
function openLightbox(index) {
  selectedIndex = index;
  const item = portfolioItems[index];
  visual.replaceChildren();
  if (item.type === 'video' && item.embed) {
    const frame = document.createElement('iframe');
    frame.src = item.embed;
    frame.title = item.title;
    frame.allow = 'autoplay; encrypted-media; picture-in-picture';
    frame.allowFullscreen = true;
    visual.append(frame);
  } else if (item.thumb) {
    const img = document.createElement('img');
    img.src = item.thumb;
    img.alt = item.title;
    img.className = 'lightbox-image';
    visual.append(img);
  }
  document.getElementById('lbTitle').textContent = item.title;
  document.getElementById('lbCat').textContent = item.category;
  document.getElementById('lbDescription').textContent = item.desc || '';
  lightbox.classList.add('open');
  document.body.style.overflow = 'hidden';
  document.getElementById('lbClose').focus();
}
function closeLightbox() {
  lightbox.classList.remove('open');
  visual.replaceChildren();
  document.body.style.overflow = '';
  opener?.focus();
}
portfolioButtons.forEach(button => button.addEventListener('click', () => {
  opener = button;
  openLightbox(Number(button.dataset.portfolioIndex));
}));
function stepLightbox(step) {
  const indices = filteredButtons.map(button => Number(button.dataset.portfolioIndex));
  if (indices.length) openLightbox(indices[(indices.indexOf(selectedIndex) + step + indices.length) % indices.length]);
}
document.getElementById('lbClose').addEventListener('click', closeLightbox);
document.getElementById('lbPrev').addEventListener('click', () => stepLightbox(-1));
document.getElementById('lbNext').addEventListener('click', () => stepLightbox(1));
lightbox.addEventListener('click', event => { if (event.target === lightbox) closeLightbox(); });
const burger = document.getElementById('burger');
const mobileMenu = document.getElementById('mobileMenu');
function setMenu(open) {
  mobileMenu.classList.toggle('open', open);
  burger.setAttribute('aria-expanded', String(open));
  document.body.style.overflow = open ? 'hidden' : '';
}
burger.addEventListener('click', () => setMenu(!mobileMenu.classList.contains('open')));
mobileMenu.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setMenu(false)));
window.matchMedia('(min-width: 861px)').addEventListener('change', event => { if (event.matches) setMenu(false); });
document.addEventListener('keydown', event => {
  if (event.key === 'Escape') { if (lightbox.classList.contains('open')) closeLightbox(); setMenu(false); }
  if (!lightbox.classList.contains('open')) return;
  if (event.key === 'ArrowRight') stepLightbox(1);
  if (event.key === 'ArrowLeft') stepLightbox(-1);
  if (event.key === 'Tab') {
    const controls = [...lightbox.querySelectorAll('button, iframe')];
    const first = controls[0], last = controls[controls.length - 1];
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
  }
});
if ('IntersectionObserver' in window) {
  const observer = new IntersectionObserver(entries => entries.forEach(entry => {
    if (entry.isIntersecting) { entry.target.classList.add('in'); observer.unobserve(entry.target); }
  }), { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(element => { element.classList.add('animate'); observer.observe(element); });
}
</script>
</body>
</html>
