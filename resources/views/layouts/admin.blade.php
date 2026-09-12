<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Kelola Konten') — Kedubes Studio Admin</title>
@if ($siteSettings->logo_url)
<link rel="icon" href="{{ $siteSettings->logo_url }}?v={{ $siteSettings->updated_at?->timestamp ?? 1 }}" sizes="any">
<link rel="apple-touch-icon" href="{{ $siteSettings->logo_url }}?v={{ $siteSettings->updated_at?->timestamp ?? 1 }}">
@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --violet:#0D47A1; --violet-deep:#082E6B;
    --gold:#8a7100; --gold-soft:#fff8cc;
    --text:#1d1d1f; --text-muted:#6e6e73; --text-faint:#9a9ca2;
    --line:rgba(0,0,0,.08); --bg:#ffffff; --bg-soft:#F5F8FF;
    --violet-soft:#EAF0FF; --green:#1e8e5a; --green-soft:#e8f7f0;
    --radius-lg:20px; --radius-md:14px; --radius-sm:9px;
    --shadow-card:0 2px 5px rgba(0,0,0,.04), 0 14px 30px rgba(0,0,0,.05);
    --ease:cubic-bezier(.16,.84,.44,1);
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'Inter',system-ui,sans-serif;background:var(--bg-soft);color:var(--text);line-height:1.5;}
  h1,h2,h3,h4{font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;letter-spacing:-.02em;}
  .mono{font-family:'IBM Plex Mono',monospace;}
  a{text-decoration:none;color:inherit;}
  button{font-family:inherit;cursor:pointer;}
  .seal{width:34px;height:34px;flex:none;}

  .shell{display:flex;min-height:100vh;}
  .sidebar{
    width:250px;flex:none;background:#fff;border-right:1px solid var(--line);
    display:flex;flex-direction:column;padding:22px 16px;position:sticky;top:0;height:100vh;
  }
  .sb-brand{display:flex;align-items:center;gap:10px;padding:6px 8px 22px;border-bottom:1px solid var(--line);margin-bottom:18px;}
  .sb-brand .seal{border-radius:8px;object-fit:contain;}
  .sb-brand div{font-weight:700;font-size:14px;}
  .sb-brand small{display:block;font-family:'IBM Plex Mono',monospace;font-size:8.5px;letter-spacing:.1em;color:var(--gold);text-transform:uppercase;}
  .sb-label{font-family:'IBM Plex Mono',monospace;font-size:10px;letter-spacing:.1em;color:var(--text-faint);text-transform:uppercase;padding:0 10px;margin:14px 0 8px;}
  .sb-menu a{
    display:flex;align-items:center;gap:11px;padding:10px 12px;border-radius:var(--radius-sm);
    font-size:13.8px;font-weight:500;color:var(--text-muted);margin-bottom:2px;transition:.15s;
  }
  .sb-menu a:hover{background:var(--bg-soft);color:var(--text);}
  .sb-menu a.active{background:var(--violet-soft);color:var(--violet-deep);font-weight:600;}
  .sb-bottom{margin-top:auto;padding-top:14px;border-top:1px solid var(--line);}
  .sb-user{display:flex;align-items:center;gap:10px;padding:8px;border-radius:var(--radius-sm);}
  .sb-user .av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--violet),var(--gold));border:1px solid var(--line);overflow:hidden;flex:none;}
  .sb-user .av-logo{width:100%;height:100%;display:block;object-fit:contain;background:#fff;padding:2px;}
  .sb-user strong{font-size:12.5px;display:block;}
  .sb-user span{font-size:11px;color:var(--text-faint);}
  .sb-user .logout{margin-left:auto;color:var(--text-faint);background:none;border:0;}

  .main{flex:1;min-width:0;}
  .topbar{
    background:rgba(255,255,255,.85);backdrop-filter:blur(10px);border-bottom:1px solid var(--line);
    padding:18px 32px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:5;
  }
  .topbar h2{font-size:19px;}
  .topbar .path{font-size:12px;color:var(--text-faint);margin-top:2px;}
  .btn-view-site{
    display:flex;align-items:center;gap:7px;padding:9px 16px;border-radius:100px;border:1px solid var(--line);
    font-size:13px;font-weight:600;background:#fff;
  }
  .content{padding:28px 32px 60px;}

  .tabs{display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;}
  .tab-btn{
    padding:9px 16px;border-radius:100px;border:1px solid var(--line);background:#fff;
    font-size:13px;font-weight:600;color:var(--text-muted);
  }
  .tab-btn.active{background:var(--text);color:#fff;border-color:var(--text);}

  .card{background:#fff;border:1px solid var(--line);border-radius:var(--radius-md);box-shadow:var(--shadow-card);margin-bottom:20px;}
  .card-head{padding:18px 22px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;}
  .card-head h3{font-size:15.5px;}
  .card-head p{font-size:12.5px;color:var(--text-muted);margin-top:2px;}
  .card-body{padding:22px;}

  .btn-primary{
    display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border:0;border-radius:100px;
    font-size:13px;font-weight:600;color:#fff;background:linear-gradient(135deg,var(--violet),var(--violet-deep));
  }
  .btn-outline{padding:8px 14px;border-radius:100px;border:1px solid var(--line);background:#fff;font-size:12.5px;font-weight:600;color:var(--text-muted);}
  .btn-danger{padding:8px 14px;border-radius:100px;border:1px solid #f3d3d3;background:#fdf1f1;font-size:12.5px;font-weight:600;color:#c0392b;}

  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
  .form-grid .full{grid-column:1/-1;}
  .field2{margin-bottom:2px;}
  .field2 label{display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;}
  .field2 input, .field2 textarea, .field2 select{
    width:100%;padding:10px 13px;border-radius:var(--radius-sm);border:1px solid var(--line);
    font-size:13.5px;font-family:inherit;background:var(--bg-soft);
  }
  .field2 textarea{resize:vertical;min-height:80px;}

  table{width:100%;border-collapse:collapse;}
  thead th{
    text-align:left;font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:var(--text-faint);
    font-family:'IBM Plex Mono',monospace;font-weight:600;padding:0 22px 12px;border-bottom:1px solid var(--line);
  }
  tbody td{padding:14px 22px;border-bottom:1px solid var(--line);font-size:13.5px;vertical-align:middle;}
  tbody tr:last-child td{border-bottom:0;}
  .thumb-cell{display:flex;align-items:center;gap:12px;}
  .thumb-cell .thumb{width:52px;height:38px;border-radius:8px;flex:none;background:linear-gradient(135deg,var(--violet),var(--gold));background-size:cover;background-position:center;}
  .thumb-cell strong{font-size:13.5px;display:block;}
  .thumb-cell span{font-size:11.5px;color:var(--text-faint);}
  .badge{font-family:'IBM Plex Mono',monospace;font-size:10.5px;padding:4px 10px;border-radius:100px;font-weight:600;}
  .badge.gambar{background:var(--violet-soft);color:var(--violet-deep);}
  .badge.video{background:var(--gold-soft);color:var(--gold);}
  .row-actions{display:flex;gap:8px;}
  .icon-btn{
    width:30px;height:30px;border-radius:8px;border:1px solid var(--line);background:#fff;
    display:flex;align-items:center;justify-content:center;color:var(--text-muted);
  }
  .icon-btn:hover{background:var(--bg-soft);color:var(--text);}
  .empty-note{padding:40px 22px;text-align:center;color:var(--text-faint);font-size:13px;}

  .admin-modal{
    width:min(760px,calc(100% - 32px));max-height:calc(100dvh - 32px);margin:auto;padding:0;
    overflow:hidden;border:1px solid var(--line);border-radius:var(--radius-lg);background:#fff;color:var(--text);
    box-shadow:0 24px 70px rgba(0,0,0,.22);
  }
  .admin-modal::backdrop{background:rgba(29,29,31,.48);backdrop-filter:blur(4px);}
  .admin-modal-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;padding:20px 22px;border-bottom:1px solid var(--line);}
  .admin-modal-head h3{font-size:17px;}
  .admin-modal-head p{margin-top:3px;color:var(--text-muted);font-size:12.5px;}
  .admin-modal-close{width:32px;height:32px;flex:none;border:1px solid var(--line);border-radius:9px;background:#fff;color:var(--text-muted);font-size:18px;}
  .admin-modal-body{max-height:calc(100dvh - 112px);padding:22px;overflow-y:auto;}
  .current-media{display:flex;align-items:center;gap:14px;padding:12px;border:1px solid var(--line);border-radius:var(--radius-sm);background:var(--bg-soft);}
  .current-media img{width:112px;height:78px;flex:none;border-radius:8px;background:#fff;object-fit:contain;}
  .current-media strong{display:block;font-size:13px;}
  .current-media span{display:block;margin-top:3px;color:var(--text-faint);font-size:11.5px;}
  .admin-modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:18px;padding-top:18px;border-top:1px solid var(--line);}

  .alert{padding:12px 18px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;margin-bottom:18px;}
  .alert-success{background:var(--green-soft);color:var(--green);}
  .alert-error{background:#fdf1f1;color:#c0392b;}

  @media (max-width:900px){
    .shell{display:block;}
    .sidebar{width:100%;height:auto;position:static;padding:14px 16px;}
    .sb-brand{padding:4px 6px 12px;margin-bottom:10px;}
    .sb-label{display:none;}
    .sb-menu a{margin-bottom:10px;}
    .sb-bottom{margin-top:0;padding-top:10px;}
    .form-grid{grid-template-columns:1fr;}
    .content{padding:22px 16px 50px;}
    .topbar{padding:14px 16px;}
    .card{overflow-x:auto;}
    table{min-width:680px;}
  }
  @media (max-width:560px){
    .topbar{align-items:flex-start;gap:12px;}
    .btn-view-site{padding:8px 12px;white-space:nowrap;}
    .card-head,.card-body{padding:16px;}
    .admin-modal{width:calc(100% - 20px);max-height:calc(100dvh - 20px);border-radius:16px;}
    .admin-modal-head,.admin-modal-body{padding:16px;}
    .admin-modal-body{max-height:calc(100dvh - 86px);}
    .current-media{align-items:flex-start;flex-direction:column;}
    .current-media img{width:100%;height:180px;}
    .admin-modal-actions{align-items:stretch;flex-direction:column-reverse;}
    .admin-modal-actions button{justify-content:center;width:100%;}
  }
</style>
@stack('styles')
</head>
<body>

<div class="shell">
  <aside class="sidebar">
    <div class="sb-brand">
      @if ($siteSettings->logo_url)
        <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->company_name }}" class="seal">
      @else
        <svg class="seal" viewBox="0 0 60 60" fill="none"><circle cx="30" cy="30" r="27" stroke="#b8791f" stroke-width="1.4" stroke-dasharray="2 3"/><circle cx="30" cy="30" r="20" stroke="#6c5ce7" stroke-width="1.4"/><path d="M30 16 L33 26 L44 26 L35 32 L38 43 L30 36 L22 43 L25 32 L16 26 L27 26 Z" fill="#b8791f"/></svg>
      @endif
      <div>{{ Str::upper($siteSettings->company_name) }}<small>Admin Panel</small></div>
    </div>

    <div class="sb-label">Pengaturan Situs</div>
    <nav class="sb-menu">
      <a href="{{ route('admin.identitas') }}" class="{{ request()->routeIs('admin.identitas', 'dashboard') ? 'active' : '' }}">Identitas</a>
      <a href="{{ route('admin.hero') }}" class="{{ request()->routeIs('admin.hero') ? 'active' : '' }}">Hero & About</a>
      <a href="{{ route('admin.kontak') }}" class="{{ request()->routeIs('admin.kontak') ? 'active' : '' }}">Kontak</a>
      <a href="{{ route('admin.klien.index') }}" class="{{ request()->routeIs('admin.klien.*') ? 'active' : '' }}">Klien</a>
      <a href="{{ route('admin.original-ip.index') }}" class="{{ request()->routeIs('admin.original-ip.*') ? 'active' : '' }}">Original IP</a>
      <a href="{{ route('admin.tim.index') }}" class="{{ request()->routeIs('admin.tim.*') ? 'active' : '' }}">Tim Studio</a>
      <a href="{{ route('admin.produk.index') }}" class="{{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">Produk</a>
    </nav>

    <div class="sb-label">Konten</div>
    <nav class="sb-menu">
      <a href="{{ route('admin.layanan.index') }}" class="{{ request()->routeIs('admin.layanan.*') ? 'active' : '' }}">Layanan</a>
      <a href="{{ route('admin.portofolio.index') }}" class="{{ request()->routeIs('admin.portofolio.*') ? 'active' : '' }}">Portofolio</a>
      <a href="{{ route('admin.testimoni.index') }}" class="{{ request()->routeIs('admin.testimoni.*') ? 'active' : '' }}">Testimoni</a>
    </nav>

    <div class="sb-bottom">
      <div class="sb-user">
        <div class="av">
          @if ($siteSettings->logo_url)
            <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->company_name }}" class="av-logo">
          @endif
        </div>
        <div><strong>{{ auth()->user()->name ?? 'Admin' }}</strong><span>{{ auth()->user()->email ?? '' }}</span></div>
        <a href="{{ route('admin.akun.edit') }}" class="logout {{ request()->routeIs('admin.akun.*') ? 'active' : '' }}" title="Akun Saya">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout" title="Keluar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <h2>@yield('title', 'Kelola Konten')</h2>
        <div class="path mono">admin/{{ request()->segment(2) }}</div>
      </div>
      <a href="{{ route('home') }}" target="_blank" class="btn-view-site">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14L21 3"/></svg>
        Lihat Situs
      </a>
    </div>

    <div class="content">
      @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-error">
          @foreach ($errors->all() as $error) {{ $error }}<br> @endforeach
        </div>
      @endif

      @yield('content')
    </div>
  </div>
</div>

@stack('scripts')
</body>
</html>
