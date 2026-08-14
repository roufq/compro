<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $settings->company_name }} — {{ $settings->tagline }}</title>
<meta name="description" content="{{ $settings->hero_description ?: 'Studio kreatif bertenaga AI untuk kebutuhan visual dan komunikasi brand.' }}">
@if ($settings->logo_url)
<link rel="icon" href="{{ $settings->logo_url }}?v={{ $settings->updated_at?->timestamp ?? 1 }}" sizes="any">
<link rel="apple-touch-icon" href="{{ $settings->logo_url }}?v={{ $settings->updated_at?->timestamp ?? 1 }}">
@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#ffffff;
    --ink-2:#f5f5f7;
    --ink-3:#ffffff;
    --ink-4:#eef0f5;
    --violet:#6c5ce7;
    --violet-deep:#4b3fe0;
    --violet-glow:rgba(108,92,231,.22);
    --gold:#b8791f;
    --gold-soft:rgba(184,121,31,.12);
    --text:#1d1d1f;
    --text-muted:#6e6e73;
    --text-faint:#8a8d93;
    --line:rgba(0,0,0,.08);
    --paper:#f6f3ea;
    --radius-lg:28px;
    --radius-md:18px;
    --radius-sm:10px;
    --maxw:1200px;
    --ease:cubic-bezier(.16,.84,.44,1);
    --shadow-card:0 2px 5px rgba(0,0,0,.04), 0 14px 34px rgba(0,0,0,.06);
    --shadow-card-hover:0 8px 18px rgba(0,0,0,.06), 0 26px 50px rgba(0,0,0,.1);
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  html{scroll-behavior:smooth;}
  body{
    background:var(--ink);
    color:var(--text);
    font-family:'Inter',system-ui,sans-serif;
    line-height:1.55;
    -webkit-font-smoothing:antialiased;
    overflow-x:hidden;
  }
  h1,h2,h3,h4{font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;letter-spacing:-.02em;line-height:1.08;}
  .mono{font-family:'IBM Plex Mono',monospace;}
  a{color:inherit;text-decoration:none;}
  img,svg{display:block;max-width:100%;}
  .wrap{max-width:var(--maxw);margin:0 auto;padding:0 28px;}
  section{position:relative;}
  ::selection{background:var(--violet);color:#fff;}

  /* background ambience */
  .bg-glow{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden;}
  .bg-glow span{position:absolute;border-radius:50%;filter:blur(130px);opacity:.16;}
  .bg-glow .g1{width:600px;height:600px;background:var(--violet);top:-220px;left:-150px;}
  .bg-glow .g2{width:500px;height:500px;background:var(--gold);top:900px;right:-200px;opacity:.1;}
  .bg-glow .g3{width:700px;height:700px;background:var(--violet-deep);bottom:-300px;left:20%;opacity:.12;}

  /* ---------- NAV ---------- */
  header.nav{
    position:fixed;top:0;left:0;right:0;z-index:100;
    backdrop-filter:blur(18px) saturate(180%);
    background:rgba(255,255,255,.72);
    border-bottom:1px solid var(--line);
  }
  .nav-inner{
    max-width:var(--maxw);margin:0 auto;padding:16px 28px;
    display:flex;align-items:center;justify-content:space-between;
  }
  .brand{display:flex;align-items:center;gap:10px;font-weight:700;font-size:16.5px;letter-spacing:.01em;}
  .brand .seal{width:32px;height:32px;flex:none;}
  .brand small{display:block;font-family:'IBM Plex Mono',monospace;font-weight:500;font-size:9.5px;letter-spacing:.14em;color:var(--gold);text-transform:uppercase;}
  nav.links{display:flex;gap:34px;align-items:center;background:transparent;}
  nav.links a{font-size:14.5px;color:var(--text-muted);transition:color .2s;position:relative;}
  nav.links a:hover{color:var(--text);}
  .nav-cta{
    background:linear-gradient(135deg,var(--violet),var(--violet-deep));
    color:#fff;padding:10px 20px;border-radius:100px;font-size:14px;font-weight:600;
    box-shadow:0 4px 18px var(--violet-glow);transition:transform .25s var(--ease), box-shadow .25s;
  }
  nav.links .nav-cta,nav.links .nav-cta:hover{color:#fff;}
  .nav-cta:hover{transform:translateY(-2px);box-shadow:0 8px 26px var(--violet-glow);}
  .burger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:0;padding:6px;}
  .burger span{width:22px;height:2px;background:var(--text);border-radius:2px;transition:.3s;}
  /* ---------- HERO ---------- */
  .hero{padding:168px 0 90px;position:relative;z-index:1;}
  .hero-inner{max-width:var(--maxw);margin:0 auto;padding:0 28px;text-align:center;}
  .eyebrow{
    display:inline-flex;align-items:center;gap:8px;
    padding:7px 16px;border-radius:100px;border:1px solid var(--line);
    background:#fff;box-shadow:var(--shadow-card);
    font-family:'IBM Plex Mono',monospace;font-size:11.5px;letter-spacing:.12em;
    color:var(--gold);text-transform:uppercase;margin-bottom:26px;
  }
  .eyebrow .dot{width:6px;height:6px;border-radius:50%;background:var(--gold);box-shadow:0 0 10px var(--gold);animation:pulse 2s infinite;}
  @keyframes pulse{0%,100%{opacity:1;}50%{opacity:.35;}}
  .hero h1{
    font-size:clamp(38px,6.4vw,84px);
    max-width:920px;margin:0 auto 24px;
    background:linear-gradient(180deg,#1d1d1f, #3a3a3d 70%, #55555a);
    -webkit-background-clip:text;background-clip:text;color:transparent;
  }
  .hero h1 em{
    font-style:normal;
    background:linear-gradient(120deg,var(--gold),#d99a2b 45%, var(--violet));
    -webkit-background-clip:text;background-clip:text;color:transparent;
  }
  .hero p.lead{
    max-width:600px;margin:0 auto 40px;color:var(--text-muted);
    font-size:clamp(16px,1.9vw,19px);
  }
  .hero-actions{display:flex;gap:14px;justify-content:center;flex-wrap:wrap;margin-bottom:88px;}
  .btn{
    padding:14px 28px;border-radius:100px;font-weight:600;font-size:15px;
    display:inline-flex;align-items:center;gap:8px;transition:all .25s var(--ease);
    cursor:pointer;border:1px solid transparent;
  }
  .btn-primary{
    background:linear-gradient(135deg,var(--violet),var(--violet-deep));
    color:#fff;box-shadow:0 6px 24px var(--violet-glow);
  }
  .btn-primary:hover{transform:translateY(-3px);box-shadow:0 12px 32px var(--violet-glow);}
  .btn-ghost{background:#f5f5f7;border-color:var(--line);color:var(--text);}
  .btn-ghost:hover{background:#ececef;border-color:rgba(0,0,0,.14);}

  /* hero visual grid — mosaic teaser of generated works */
  .hero-mosaic{
    max-width:1080px;margin:0 auto;
    display:grid;grid-template-columns:repeat(6,1fr);grid-auto-rows:80px;gap:12px;
    -webkit-mask-image:linear-gradient(to bottom, black 60%, transparent 100%);
    mask-image:linear-gradient(to bottom, black 60%, transparent 100%);
  }
  .hero-mosaic .tile{border-radius:16px;position:relative;overflow:hidden;}
  .hero-mosaic .tile.a{grid-column:span 2;grid-row:span 3;}
  .hero-mosaic .tile.b{grid-column:span 2;grid-row:span 2;}
  .hero-mosaic .tile.c{grid-column:span 2;grid-row:span 4;}
  .hero-mosaic .tile.d{grid-column:span 2;grid-row:span 2;}
  .hero-mosaic .tile.e{grid-column:span 2;grid-row:span 3;}
  .hero-mosaic .tile.f{grid-column:span 2;grid-row:span 2;}
  .hero-media-card{position:absolute;inset:0;width:100%;height:100%;padding:0;border:0;background:none;color:#fff;cursor:pointer;text-align:left;}
  .hero-media-card img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform .45s var(--ease);}
  .hero-media-card:hover img{transform:scale(1.04);}
  .hero-media-card::after{content:"";position:absolute;inset:0;background:linear-gradient(to top,rgba(12,12,20,.72),transparent 58%);}
  .hero-media-meta{position:absolute;inset:0;z-index:2;display:block;}
  .hero-media-meta>span:first-child{position:absolute;left:14px;right:14px;bottom:13px;}
  .hero-media-meta strong{display:block;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;line-height:1.25;text-shadow:0 1px 8px rgba(0,0,0,.35);}
  .hero-media-meta span{display:block;margin-top:3px;font-family:'IBM Plex Mono',monospace;font-size:8px;letter-spacing:.08em;text-transform:uppercase;opacity:.82;}
  .hero-media-meta .hero-play{position:absolute;left:50%;top:50%;display:grid;place-items:center;width:40px;height:40px;margin:0;border-radius:50%;color:var(--violet-deep);opacity:1;background:rgba(255,255,255,.94);border:1px solid rgba(255,255,255,.98);box-shadow:0 6px 18px rgba(0,0,0,.26);backdrop-filter:blur(8px);transform:translate(-50%,-50%);transition:transform .2s var(--ease),color .2s,background .2s,box-shadow .2s;}
  .hero-play svg{width:14px;height:14px;transform:translateX(1px);}
  .hero-media-card:hover .hero-play{color:#fff;transform:translate(-50%,-50%) scale(1.06);background:var(--violet);box-shadow:0 8px 22px rgba(74,61,214,.34);}

  /* generated art texture tiles */
  .art{position:absolute;inset:0;}
  .art::after{
    content:"";position:absolute;inset:0;opacity:.5;mix-blend-mode:overlay;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  }
  .art-1{background:linear-gradient(135deg,#5a4bd8,#8f7bf8 40%,#b8791f);}
  .art-2{background:linear-gradient(135deg,#0f2e4c,#2f6fb0 50%,#7b6ef6);}
  .art-3{background:linear-gradient(135deg,#3a1f5c,#c14fa0 55%,#f3d688);}
  .art-4{background:linear-gradient(135deg,#173a2e,#2f9e73 50%,#c8f26b);}
  .art-5{background:linear-gradient(135deg,#4a1f2e,#e0577a 55%,#f7b6a3);}
  .art-6{background:linear-gradient(135deg,#1a2140,#4d3fe0 45%,#9c8bff);}
  .art-7{background:linear-gradient(135deg,#332107,#b8791f 50%,#fff2c9);}
  .art-8{background:linear-gradient(135deg,#0e2436,#1c7a8c 50%,#7bf6d8);}
  .art-9{background:linear-gradient(135deg,#3d1030,#a13cad 50%,#f6a1e0);}
  .art-10{background:linear-gradient(135deg,#101a3d,#3f56c9 45%,#8ff6ff);}
  .art-11{background:linear-gradient(135deg,#2c1a05,#b8752c 50%,#ffd9a0);}
  .art-12{background:linear-gradient(135deg,#031b1a,#0f8a7a 50%,#c6ffdd);}

  /* ---------- STATS / STAMPS ---------- */
  .stats{padding:60px 0 110px;position:relative;z-index:1;}
  .stats .wrap{
    display:grid;grid-template-columns:repeat(4,1fr);gap:18px;
    border-top:1px dashed var(--line);border-bottom:1px dashed var(--line);
    padding:40px 28px;
  }
  .stamp-card{text-align:center;display:flex;flex-direction:column;align-items:center;gap:10px;}
  .stamp-card .num{font-size:clamp(26px,3vw,38px);font-weight:800;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text);}
  .stamp-card .num span{color:var(--gold);}
  .stamp-card .label{font-family:'IBM Plex Mono',monospace;font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--text-faint);}

  /* ---------- SECTION HEADS ---------- */
  .section-head{max-width:640px;margin:0 auto 56px;text-align:center;}
  .section-head .kicker{
    font-family:'IBM Plex Mono',monospace;font-size:11.5px;letter-spacing:.14em;
    text-transform:uppercase;color:var(--gold);margin-bottom:14px;display:block;
  }
  .section-head h2{font-size:clamp(28px,4vw,44px);margin-bottom:16px;}
  .section-head p{color:var(--text-muted);font-size:16.5px;}

  /* ---------- SERVICES ---------- */
  .services{padding:70px 0;position:relative;z-index:1;}
  .svc-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;}
  .svc-card{
    background:#fff;
    border:1px solid var(--line);border-radius:var(--radius-md);
    padding:28px 24px;transition:transform .3s var(--ease), box-shadow .3s, border-color .3s;
    box-shadow:var(--shadow-card);
  }
  .svc-card:hover{transform:translateY(-6px);border-color:rgba(108,92,231,.3);box-shadow:var(--shadow-card-hover);}
  .svc-icon{
    width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;
    background:linear-gradient(135deg,var(--violet),var(--violet-deep));margin-bottom:20px;
  }
  .svc-card h3{font-size:18px;margin-bottom:10px;}
  .svc-card p{color:var(--text-muted);font-size:14.5px;}

  /* ---------- PORTFOLIO ---------- */
  .portfolio{padding:100px 0 60px;position:relative;z-index:1;}
  .filters{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-bottom:44px;}
  .filter-btn{
    font-family:'IBM Plex Mono',monospace;font-size:12px;letter-spacing:.06em;text-transform:uppercase;
    padding:9px 18px;border-radius:100px;border:1px solid var(--line);color:var(--text-muted);
    background:transparent;cursor:pointer;transition:all .2s;
  }
  .filter-btn:hover{color:var(--text);border-color:rgba(0,0,0,.22);}
  .filter-btn.active{background:var(--text);color:#fff;border-color:var(--text);font-weight:600;}

  .grid{
    columns:4 260px;column-gap:18px;
  }
  .card{
    break-inside:avoid;margin-bottom:18px;border-radius:20px;overflow:hidden;position:relative;
    cursor:pointer;border:1px solid var(--line);background:#fff;
    transition:transform .35s var(--ease), box-shadow .35s;
    box-shadow:var(--shadow-card);
  }
  .card:hover{transform:translateY(-5px);box-shadow:var(--shadow-card-hover);}
  .card .art{border-radius:20px;}
  .card .thumb{position:relative;width:100%;}
  .card .badge{
    position:absolute;top:12px;left:12px;z-index:2;
    display:inline-flex;align-items:center;justify-content:center;min-height:26px;
    font-family:'IBM Plex Mono',monospace;font-size:10px;font-weight:600;line-height:1;letter-spacing:.1em;text-transform:uppercase;color:#fff;
    background:rgba(15,15,25,.78);backdrop-filter:blur(10px);box-shadow:0 4px 14px rgba(0,0,0,.22);
    padding:6px 11px;border-radius:100px;border:1px solid rgba(255,255,255,.42);text-shadow:0 1px 3px rgba(0,0,0,.5);
  }
  .card .play{
    position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:2;
  }
  .card .play span{
    width:52px;height:52px;border-radius:50%;background:rgba(10,14,26,.55);backdrop-filter:blur(6px);
    display:flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.3);
  }
  .card .meta{padding:14px 16px 16px;position:relative;z-index:2;}
  .card .meta h4{font-size:14.5px;font-weight:600;margin-bottom:3px;}
  .card .meta span{font-size:12px;color:var(--text-faint);}
  .card .stamp-mini{position:absolute;bottom:14px;right:14px;width:44px;height:44px;opacity:.9;z-index:2;}

  /* lightbox */
  .lightbox{
    position:fixed;inset:0;z-index:200;background:rgba(245,245,247,.9);backdrop-filter:blur(12px);
    display:none;align-items:center;justify-content:center;padding:30px;
  }
  .lightbox.open{display:flex;}
  .lb-box{max-width:min(1100px,94vw);width:100%;max-height:92vh;background:#fff;border:1px solid var(--line);border-radius:24px;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,.18);}
  .lb-art{width:100%;position:relative;display:flex;align-items:center;justify-content:center;background:#f5f5f7;overflow:hidden;}
  .lb-art.is-image{height:min(72vh,720px);min-height:320px;padding:18px;}
  .lb-art.is-video{aspect-ratio:16/9;}
  .lightbox-image{display:block;width:100%;height:100%;object-fit:contain;}
  .lb-info{padding:24px 28px;display:flex;justify-content:space-between;align-items:flex-start;gap:20px;}
  .lb-info h3{font-size:20px;margin-bottom:6px;}
  .lb-info p{color:var(--text-muted);font-size:14px;}
  .lb-close{
    width:38px;height:38px;border-radius:50%;background:#f5f5f7;border:1px solid var(--line);
    display:flex;align-items:center;justify-content:center;cursor:pointer;flex:none;color:var(--text);font-size:18px;
  }

  /* ---------- PROCESS ---------- */
  .process{padding:120px 0;position:relative;z-index:1;}
  .steps{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;position:relative;}
  .steps::before{
    content:"";position:absolute;top:34px;left:5%;right:5%;height:1px;
    background:repeating-linear-gradient(90deg,var(--line) 0 8px, transparent 8px 16px);
    z-index:0;
  }
  .step{position:relative;z-index:1;text-align:center;padding:0 8px;}
  .step .stamp-wrap{width:68px;height:68px;margin:0 auto 20px;}
  .step h4{font-size:15px;margin-bottom:8px;}
  .step p{font-size:13px;color:var(--text-muted);}
  .step .idx{font-family:'IBM Plex Mono',monospace;font-size:11px;color:var(--gold);display:block;margin-bottom:6px;}

  /* ---------- TESTIMONIALS ---------- */
  .testi{padding:90px 0;position:relative;z-index:1;}
  .testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;}
  .testi-card{
    background:#fff;border:1px solid var(--line);border-radius:var(--radius-md);
    padding:26px 24px;display:flex;flex-direction:column;gap:16px;
    box-shadow:var(--shadow-card);
  }
  .testi-card .quote{font-size:15px;color:var(--text);line-height:1.65;}
  .testi-card .stars{color:var(--gold);font-size:13px;letter-spacing:2px;}
  .testi-who{display:flex;align-items:center;gap:10px;margin-top:auto;}
  .testi-avatar{width:38px;height:38px;border-radius:50%;flex:none;}
  .testi-who strong{font-size:13.5px;display:block;}
  .testi-who span{font-size:12px;color:var(--text-faint);}

  /* ---------- CTA ---------- */
  .cta{padding:70px 0 130px;position:relative;z-index:1;}
  .cta-box{
    max-width:var(--maxw);margin:0 auto;padding:70px 40px;text-align:center;border-radius:36px;
    background:radial-gradient(120% 160% at 50% 0%, rgba(108,92,231,.14), transparent 60%), #f5f5f7;
    border:1px solid var(--line);position:relative;overflow:hidden;
  }
  .cta-box h2{font-size:clamp(28px,4.4vw,46px);max-width:560px;margin:0 auto 18px;}
  .cta-box p{color:var(--text-muted);max-width:460px;margin:0 auto 34px;}
  .cta-seal{position:absolute;top:-30px;right:-30px;width:180px;height:180px;opacity:.15;}

  /* ---------- FOOTER ---------- */
  footer{border-top:1px solid var(--line);padding:56px 0 34px;position:relative;z-index:1;}
  .foot-top{display:flex;justify-content:space-between;gap:40px;flex-wrap:wrap;margin-bottom:44px;}
  .foot-brand p{max-width:280px;color:var(--text-muted);font-size:14px;margin-top:14px;}
  .foot-cols{display:flex;gap:60px;flex-wrap:wrap;}
  .foot-col h5{font-family:'IBM Plex Mono',monospace;font-size:11.5px;letter-spacing:.1em;text-transform:uppercase;color:var(--text-faint);margin-bottom:16px;}
  .foot-col a{display:block;font-size:14px;color:var(--text-muted);margin-bottom:11px;transition:color .2s;}
  .foot-col a:hover{color:var(--text);}
  .foot-bottom{
    display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;
    padding-top:26px;border-top:1px solid var(--line);font-size:12.5px;color:var(--text-faint);
  }
  .socials{display:flex;gap:12px;}
  .socials a{width:36px;height:36px;border-radius:50%;border:1px solid var(--line);display:flex;align-items:center;justify-content:center;transition:.2s;color:var(--text-muted);}
  .socials a:hover{border-color:var(--violet);color:var(--violet);}

  /* ---------- RESPONSIVE ---------- */
  @media (max-width:980px){
    .svc-grid{grid-template-columns:repeat(2,1fr);}
    .stats .wrap{grid-template-columns:repeat(2,1fr);row-gap:30px;}
    .testi-grid{grid-template-columns:1fr;}
    .steps{grid-template-columns:repeat(5,1fr);}
    .grid{columns:3 220px;}
  }
  @media (max-width:760px){
    nav.links{position:fixed;top:66px;left:0;right:0;bottom:0;background:var(--ink);
      flex-direction:column;padding:40px 28px;gap:26px;transform:translateX(100%);transition:transform .35s var(--ease);
      border-top:1px solid var(--line);}
    nav.links.open{transform:translateX(0);}
    nav.links a{font-size:20px;}
    .burger{display:flex;}
    .hero{padding-top:130px;}
    .hero-mosaic{grid-template-columns:repeat(3,1fr);grid-auto-rows:60px;}
    .svc-grid{grid-template-columns:1fr;}
    .steps{grid-template-columns:1fr;gap:30px;}
    .steps::before{display:none;}
    .grid{columns:2 160px;}
    .cta-box{padding:50px 24px;border-radius:26px;}
    .foot-top{flex-direction:column;}
  }
  @media (max-width:480px){
    .grid{columns:1;}
    .hero-actions{flex-direction:column;align-items:stretch;}
    .lb-art.is-image{height:min(64vh,520px);min-height:240px;padding:10px;}
  }

  @media (prefers-reduced-motion:reduce){
    *{animation:none!important;transition:none!important;}
    html{scroll-behavior:auto;}
  }


  /* video embed di lightbox */
  .lb-art iframe{width:100%;height:100%;border:0;position:absolute;inset:0;}
  .card .thumb img{width:100%;height:100%;object-fit:cover;position:absolute;inset:0;}
</style>
</head>
<body>

<div class="bg-glow"><span class="g1"></span><span class="g2"></span><span class="g3"></span></div>

<!-- ================= NAV ================= -->
<header class="nav">
  <div class="nav-inner">
    <div class="brand">
      @if ($settings->logo_url)
        <img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }}" class="seal" style="border-radius:8px;object-fit:cover;">
      @else
        <svg class="seal" viewBox="0 0 60 60" fill="none"><circle cx="30" cy="30" r="27" stroke="#b8791f" stroke-width="1.4" stroke-dasharray="2 3"/><circle cx="30" cy="30" r="20" stroke="#7b6ef6" stroke-width="1.4"/><path d="M30 16 L33 26 L44 26 L35 32 L38 43 L30 36 L22 43 L25 32 L16 26 L27 26 Z" fill="#b8791f"/></svg>
      @endif
      <div>{{ strtoupper($settings->company_name) }}<small>{{ $settings->tagline }}</small></div>
    </div>
    <nav class="links" id="navLinks">
      <a href="#layanan">Layanan</a>
      <a href="#portofolio">Portofolio</a>
      <a href="#proses">Proses</a>
      <a href="#testimoni">Testimoni</a>
      <a href="#kontak" class="nav-cta">Ajukan Proyek</a>
    </nav>
    <button class="burger" id="burger" aria-label="Buka menu"><span></span><span></span><span></span></button>
  </div>
</header>

<!-- ================= HERO ================= -->
<section class="hero">
  <div class="hero-inner">
    <span class="eyebrow"><span class="dot"></span> Studio Kreatif Bertenaga AI</span>
    <h1>{{ $settings->hero_title ?: 'Kreativitas tanpa batas, diperkuat kecerdasan buatan' }}</h1>
    <p class="lead">{{ $settings->hero_description ?: 'Kami memadukan strategi kreatif, teknologi AI, dan sentuhan manusia untuk menghasilkan karya visual yang berkesan.' }}</p>
    <div class="hero-actions">
      <a href="#portofolio" class="btn btn-primary">Jelajahi Portofolio ↓</a>
      <a href="#kontak" class="btn btn-ghost">Mulai Konsultasi</a>
    </div>
  </div>
  <div class="hero-mosaic">
    @php
      $heroTileClasses = ['a', 'b', 'c', 'd', 'e', 'f'];
      $heroPortfolios = $portfolios->take(6)->values();
    @endphp

    @foreach ($heroTileClasses as $heroIndex => $heroTileClass)
      @php($heroPortfolio = $heroPortfolios->get($heroIndex))
      <div class="tile {{ $heroTileClass }}">
        <div class="art art-{{ $heroIndex + 1 }}">
          @if ($heroPortfolio?->thumbnail_url)
            <button
              type="button"
              class="hero-media-card"
              data-portfolio-index="{{ $heroIndex }}"
              aria-label="Buka {{ $heroPortfolio->title }}"
            >
              <img src="{{ $heroPortfolio->thumbnail_url }}" alt="{{ $heroPortfolio->title }}">
              <span class="hero-media-meta">
                <span>
                  <strong>{{ $heroPortfolio->title }}</strong>
                  <span>{{ $heroPortfolio->category }} · {{ $heroPortfolio->type }}</span>
                </span>
                @if ($heroPortfolio->isVideo())
                  <span class="hero-play" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                  </span>
                @endif
              </span>
            </button>
          @endif
        </div>
      </div>
    @endforeach
  </div>
</section>

<!-- ================= STATS ================= -->
<section class="stats">
  <div class="wrap">
    <div class="stamp-card"><div class="num"><span>500</span>+</div><div class="label">Karya Diciptakan</div></div>
    <div class="stamp-card"><div class="num"><span>80</span>+</div><div class="label">Klien Terlayani</div></div>
    <div class="stamp-card"><div class="num"><span>15</span></div><div class="label">Negara Terjangkau</div></div>
    <div class="stamp-card"><div class="num"><span>24</span>/7</div><div class="label">Studio AI Aktif</div></div>
  </div>
</section>

<!-- ================= SERVICES (dinamis dari database) ================= -->
<section class="services" id="layanan">
  <div class="wrap">
    <div class="section-head">
      <span class="kicker">Layanan</span>
      <h2>Satu studio, semua kebutuhan visual</h2>
      <p>Dari ide mentah sampai file siap tayang — kami rancang alur kerja AI yang cepat tanpa mengorbankan kualitas.</p>
    </div>
    <div class="svc-grid">
      @forelse ($services as $service)
      <div class="svc-card">
        <div class="svc-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 20l4-1 11-11-3-3L5 16l-1 4z" stroke="#fff" stroke-width="1.6" stroke-linejoin="round"/></svg></div>
        <h3>{{ $service->title }}</h3>
        <p>{{ $service->description }}</p>
      </div>
      @empty
      <p style="color:var(--text-muted);">Belum ada layanan ditambahkan.</p>
      @endforelse
    </div>
  </div>
</section>

<!-- ================= PORTFOLIO (dinamis dari database) ================= -->
<section class="portfolio" id="portofolio">
  <div class="wrap">
    <div class="section-head">
      <span class="kicker">Portofolio</span>
      <h2>Cap persetujuan untuk setiap karya</h2>
      <p>Setiap proyek yang lolos studio kami mendapat "cap resmi" — jelajahi galeri lengkap gambar dan video hasil kolaborasi AI &amp; tim kreatif.</p>
    </div>
    <div class="filters" id="filters">
      <button class="filter-btn active" data-filter="semua">Semua</button>
      <button class="filter-btn" data-filter="gambar">Gambar</button>
      <button class="filter-btn" data-filter="video">Video</button>
      @foreach ($portfolios->pluck('category')->unique() as $cat)
      <button class="filter-btn" data-filter="{{ $cat }}">{{ ucfirst($cat) }}</button>
      @endforeach
    </div>
    <div class="grid" id="grid"></div>
  </div>
</section>

<!-- ================= PROCESS ================= -->
<section class="process" id="proses">
  <div class="wrap">
    <div class="section-head">
      <span class="kicker">Proses Kerja</span>
      <h2>Perjalanan sebuah paspor kreativitas</h2>
      <p>Lima tahap resmi, dari pengajuan hingga karya "berangkat" ke tangan Anda.</p>
    </div>
    <div class="steps">
      <div class="step">
        <div class="stamp-wrap"><svg viewBox="0 0 68 68"><circle cx="34" cy="34" r="30" fill="none" stroke="#b8791f" stroke-width="1.4" stroke-dasharray="2 3"/><text x="34" y="30" text-anchor="middle" fill="#b8791f" font-family="IBM Plex Mono" font-size="9" font-weight="600">VISA</text><text x="34" y="41" text-anchor="middle" fill="#8a8d93" font-family="IBM Plex Mono" font-size="7">01</text></svg></div>
        <span class="idx">Tahap 01</span><h4>Pengajuan &amp; Konsultasi</h4><p>Ceritakan kebutuhan visual Anda, kami susun brief kreatif bersama.</p>
      </div>
      <div class="step">
        <div class="stamp-wrap"><svg viewBox="0 0 68 68"><circle cx="34" cy="34" r="30" fill="none" stroke="#7b6ef6" stroke-width="1.4"/><text x="34" y="30" text-anchor="middle" fill="#7b6ef6" font-family="IBM Plex Mono" font-size="8" font-weight="600">RISET</text><text x="34" y="41" text-anchor="middle" fill="#8a8d93" font-family="IBM Plex Mono" font-size="7">02</text></svg></div>
        <span class="idx">Tahap 02</span><h4>Riset &amp; Konsep</h4><p>Eksplorasi gaya visual dan referensi yang sesuai identitas brand.</p>
      </div>
      <div class="step">
        <div class="stamp-wrap"><svg viewBox="0 0 68 68"><circle cx="34" cy="34" r="30" fill="none" stroke="#b8791f" stroke-width="1.4" stroke-dasharray="2 3"/><text x="34" y="30" text-anchor="middle" fill="#b8791f" font-family="IBM Plex Mono" font-size="7.5" font-weight="600">PRODUKSI</text><text x="34" y="41" text-anchor="middle" fill="#8a8d93" font-family="IBM Plex Mono" font-size="7">03</text></svg></div>
        <span class="idx">Tahap 03</span><h4>Produksi AI</h4><p>Model AI kami menciptakan puluhan variasi gambar dan video.</p>
      </div>
      <div class="step">
        <div class="stamp-wrap"><svg viewBox="0 0 68 68"><circle cx="34" cy="34" r="30" fill="none" stroke="#7b6ef6" stroke-width="1.4"/><text x="34" y="30" text-anchor="middle" fill="#7b6ef6" font-family="IBM Plex Mono" font-size="8" font-weight="600">REVISI</text><text x="34" y="41" text-anchor="middle" fill="#8a8d93" font-family="IBM Plex Mono" font-size="7">04</text></svg></div>
        <span class="idx">Tahap 04</span><h4>Kurasi &amp; Revisi</h4><p>Pilih dan sempurnakan hasil terbaik bersama tim kreatif kami.</p>
      </div>
      <div class="step">
        <div class="stamp-wrap"><svg viewBox="0 0 68 68"><circle cx="34" cy="34" r="30" fill="none" stroke="#b8791f" stroke-width="1.4" stroke-dasharray="2 3"/><text x="34" y="30" text-anchor="middle" fill="#b8791f" font-family="IBM Plex Mono" font-size="7.5" font-weight="600">LUNAS</text><text x="34" y="41" text-anchor="middle" fill="#8a8d93" font-family="IBM Plex Mono" font-size="7">05</text></svg></div>
        <span class="idx">Tahap 05</span><h4>Pengiriman Karya</h4><p>File final dikirim, siap dipakai untuk kebutuhan Anda.</p>
      </div>
    </div>
  </div>
</section>

<!-- ================= TESTIMONIALS (dinamis dari database) ================= -->
<section class="testi" id="testimoni">
  <div class="wrap">
    <div class="section-head">
      <span class="kicker">Testimoni</span>
      <h2>Kata mereka yang sudah "berkunjung"</h2>
    </div>
    <div class="testi-grid">
      @forelse ($testimonials as $t)
      <div class="testi-card">
        <div class="stars">{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</div>
        <p class="quote">"{{ $t->quote }}"</p>
        <div class="testi-who">
          @if ($t->avatar_url)
            <img src="{{ $t->avatar_url }}" class="testi-avatar" style="object-fit:cover;">
          @else
            <div class="testi-avatar" style="background:linear-gradient(135deg,var(--violet),var(--gold));"></div>
          @endif
          <div><strong>{{ $t->name }}</strong><span>{{ $t->role }}</span></div>
        </div>
      </div>
      @empty
      <p style="color:var(--text-muted);">Belum ada testimoni.</p>
      @endforelse
    </div>
  </div>
</section>

<!-- ================= CTA ================= -->
<section class="cta" id="kontak">
  <div class="wrap">
    <div class="cta-box">
      <svg class="cta-seal" viewBox="0 0 60 60" fill="none"><circle cx="30" cy="30" r="27" stroke="#b8791f" stroke-width="1.4" stroke-dasharray="2 3"/><circle cx="30" cy="30" r="20" stroke="#7b6ef6" stroke-width="1.4"/></svg>
      <h2>Ajukan paspor kreativitas Anda sekarang</h2>
      <p>Ceritakan proyek Anda dan dapatkan konsep visual pertama dalam 48 jam.</p>
      <a href="mailto:{{ $settings->email }}" class="btn btn-primary">Hubungi Kami →</a>
    </div>
  </div>
</section>

<!-- ================= FOOTER ================= -->
<footer>
  <div class="wrap">
    <div class="foot-top">
      <div class="foot-brand">
        <div class="brand">
          @if ($settings->logo_url)
            <img src="{{ $settings->logo_url }}" class="seal" style="border-radius:8px;object-fit:cover;">
          @else
            <svg class="seal" viewBox="0 0 60 60" fill="none"><circle cx="30" cy="30" r="27" stroke="#b8791f" stroke-width="1.4" stroke-dasharray="2 3"/><circle cx="30" cy="30" r="20" stroke="#7b6ef6" stroke-width="1.4"/><path d="M30 16 L33 26 L44 26 L35 32 L38 43 L30 36 L22 43 L25 32 L16 26 L27 26 Z" fill="#b8791f"/></svg>
          @endif
          <div>{{ strtoupper($settings->company_name) }}</div>
        </div>
        <p>{{ $settings->tagline }}. {{ $settings->address }}</p>
      </div>
      <div class="foot-cols">
        <div class="foot-col">
          <h5>Navigasi</h5>
          <a href="#layanan">Layanan</a>
          <a href="#portofolio">Portofolio</a>
          <a href="#proses">Proses</a>
          <a href="#testimoni">Testimoni</a>
        </div>
        <div class="foot-col">
          <h5>Kontak</h5>
          @if ($settings->email)<a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a>@endif
          @if ($settings->phone)<a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a>@endif
          @if ($settings->address)<a href="#">{{ $settings->address }}</a>@endif
        </div>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© {{ date('Y') }} {{ $settings->company_name }}. Semua karya dilindungi hak cipta.</span>
      <div class="socials">
        @if ($settings->instagram_url)
        <a href="{{ $settings->instagram_url }}" target="_blank" aria-label="Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.6"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg></a>
        @endif
        @if ($settings->linkedin_url)
        <a href="{{ $settings->linkedin_url }}" target="_blank" aria-label="LinkedIn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.6"/><circle cx="8" cy="8.5" r="1" fill="currentColor"/><path d="M8 11v6M12 17v-4a2 2 0 0 1 4 0v4M12 13v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></a>
        @endif
        @if ($settings->youtube_url)
        <a href="{{ $settings->youtube_url }}" target="_blank" aria-label="YouTube"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="12" rx="4" stroke="currentColor" stroke-width="1.6"/><path d="M10.5 10l5 2.2-5 2.2z" fill="currentColor"/></svg></a>
        @endif
      </div>
    </div>
  </div>
</footer>

<!-- ================= LIGHTBOX ================= -->
<div class="lightbox" id="lightbox">
  <div class="lb-box">
    <div class="lb-art" id="lbArt"></div>
    <div class="lb-info">
      <div><h3 id="lbTitle"></h3><p id="lbDesc"></p></div>
      <button type="button" class="lb-close" id="lbClose" aria-label="Tutup pratinjau">✕</button>
    </div>
  </div>
</div>

<script>
  const burger = document.getElementById('burger');
  const navLinks = document.getElementById('navLinks');
  burger.addEventListener('click', () => navLinks.classList.toggle('open'));
  navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', () => navLinks.classList.remove('open')));

  // data portofolio dikirim langsung dari database Laravel
  const items = {{ Js::from($portfolioItems) }};
  const escapeHtml = (value) => {
    const element = document.createElement('div');
    element.textContent = value ?? '';

    return element.innerHTML;
  };

  const grid = document.getElementById('grid');
  function renderGrid(filter){
    grid.innerHTML = "";
    items
      .filter(it => filter === "semua" || it.category === filter || it.type === filter)
      .forEach((it, i) => {
        const card = document.createElement('div');
        card.className = 'card';
        const title = escapeHtml(it.title);
        const category = escapeHtml(it.category);
        const img = it.thumb ? `<img src="${it.thumb}" alt="${title}">` : '';
        card.innerHTML = `
          <div class="thumb" style="height:${180 + (i % 3) * 60}px;">
            ${img}
            <span class="badge">${it.type === 'video' ? 'Video' : 'Gambar'}</span>
            ${it.type === 'video' ? `<div class="play"><span><svg width="16" height="16" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg></span></div>` : ''}
            <svg class="stamp-mini" viewBox="0 0 44 44"><circle cx="22" cy="22" r="19" fill="none" stroke="#b8791f" stroke-width="1.2" stroke-dasharray="1.5 2.5" opacity=".8"/></svg>
          </div>
          <div class="meta"><h4>${title}</h4><span>${category.charAt(0).toUpperCase() + category.slice(1)}</span></div>
        `;
        card.addEventListener('click', () => openLightbox(it));
        grid.appendChild(card);
      });
  }
  renderGrid('semua');

  document.getElementById('filters').addEventListener('click', (e) => {
    if(!e.target.classList.contains('filter-btn')) return;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    e.target.classList.add('active');
    renderGrid(e.target.dataset.filter);
  });

  const lightbox = document.getElementById('lightbox');
  const lbArt = document.getElementById('lbArt');
  const lbTitle = document.getElementById('lbTitle');
  const lbDesc = document.getElementById('lbDesc');
  function openLightbox(it){
    lbArt.className = `lb-art ${it.type === 'video' && it.embed ? 'is-video' : 'is-image'}`;
    lbArt.innerHTML = it.type === 'video' && it.embed
      ? `<iframe src="${it.embed}" allow="autoplay; encrypted-media" allowfullscreen></iframe>`
      : (it.thumb ? `<img src="${it.thumb}" class="lightbox-image" alt="">` : '');
    lbTitle.textContent = it.title;
    lbDesc.textContent = it.desc || '';
    lightbox.classList.add('open');
  }
  document.querySelectorAll('[data-portfolio-index]').forEach((tile) => {
    tile.addEventListener('click', () => openLightbox(items[Number(tile.dataset.portfolioIndex)]));
  });
  document.getElementById('lbClose').addEventListener('click', () => lightbox.classList.remove('open'));
  lightbox.addEventListener('click', (e) => { if(e.target === lightbox){ lightbox.classList.remove('open'); lbArt.innerHTML=''; } });
  document.addEventListener('keydown', (e) => { if(e.key === 'Escape'){ lightbox.classList.remove('open'); lbArt.innerHTML=''; } });

  const header = document.querySelector('header.nav');
  window.addEventListener('scroll', () => {
    header.style.boxShadow = window.scrollY > 10 ? '0 4px 20px rgba(0,0,0,.06)' : 'none';
  });
</script>
</body>
</html>
