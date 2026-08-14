@extends('layouts.admin')

@section('title', 'Pengaturan Umum')

@section('content')
<form method="POST" action="{{ route('admin.konten.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="card">
    <div class="card-head">
      <div>
        <h3>Pengaturan Umum</h3>
        <p>Logo, headline, dan info kontak yang tampil di halaman utama</p>
      </div>
      <button type="submit" class="btn-primary">Simpan Perubahan</button>
    </div>
    <div class="card-body">
      <div class="form-grid">

        <div class="field2 full">
          <label>Logo Perusahaan</label>
          <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:56px;height:56px;border-radius:14px;background:#f1effd;display:flex;align-items:center;justify-content:center;border:1px dashed #6c5ce7;overflow:hidden;">
              @if ($settings->logo_url)
                <img src="{{ $settings->logo_url }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;">
              @else
                <svg width="22" height="22" viewBox="0 0 60 60" fill="none"><circle cx="30" cy="30" r="27" stroke="#b8791f" stroke-width="2" stroke-dasharray="2 3"/><circle cx="30" cy="30" r="20" stroke="#6c5ce7" stroke-width="2"/></svg>
              @endif
            </div>
            <label class="btn-outline" style="cursor:pointer;">
              Ganti Logo
              <input type="file" name="logo" accept="image/*" style="display:none;">
            </label>
          </div>
        </div>

        <div class="field2">
          <label>Nama Perusahaan</label>
          <input type="text" name="company_name" value="{{ old('company_name', $settings->company_name) }}" required>
        </div>

        <div class="field2">
          <label>Tagline</label>
          <input type="text" name="tagline" value="{{ old('tagline', $settings->tagline) }}">
        </div>

        <div class="field2 full">
          <label>Judul Hero</label>
          <input type="text" name="hero_title" value="{{ old('hero_title', $settings->hero_title) }}">
        </div>

        <div class="field2 full">
          <label>Deskripsi Hero</label>
          <textarea name="hero_description">{{ old('hero_description', $settings->hero_description) }}</textarea>
        </div>

        <div class="field2">
          <label>Email</label>
          <input type="email" name="email" value="{{ old('email', $settings->email) }}">
        </div>

        <div class="field2">
          <label>No. Telepon</label>
          <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}">
        </div>

        <div class="field2">
          <label>Alamat</label>
          <input type="text" name="address" value="{{ old('address', $settings->address) }}">
        </div>

        <div class="field2">
          <label>Instagram URL</label>
          <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}">
        </div>

        <div class="field2">
          <label>LinkedIn URL</label>
          <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}">
        </div>

        <div class="field2">
          <label>YouTube URL</label>
          <input type="url" name="youtube_url" value="{{ old('youtube_url', $settings->youtube_url) }}">
        </div>

      </div>
    </div>
  </div>
</form>
@endsection
