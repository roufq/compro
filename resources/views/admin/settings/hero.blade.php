@extends('layouts.admin')

@section('title', 'Hero & About')

@section('content')
<form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="card">
    <div class="card-head">
      <div>
        <h3>Hero & About Us</h3>
        <p>Bagian sambutan utama dan profil singkat studio di halaman depan.</p>
      </div>
      <button type="submit" class="btn-primary">Simpan Perubahan</button>
    </div>
    <div class="card-body">
      <div class="form-grid">

        <div class="field2 full">
          <label>Judul Hero</label>
          <input type="text" name="hero_title" value="{{ old('hero_title', $settings->hero_title) }}">
        </div>

        <div class="field2 full">
          <label>Deskripsi Hero</label>
          <textarea name="hero_description">{{ old('hero_description', $settings->hero_description) }}</textarea>
        </div>

        <div class="field2 full">
          <label for="hero_video_url">Video Hero (YouTube)</label>
          <input id="hero_video_url" name="hero_video_url" type="url" value="{{ old('hero_video_url', $settings->hero_video_url) }}" maxlength="2048" placeholder="https://youtu.be/...">
          <small>Jika diisi, video ini akan diputar otomatis (autoplay, bisu) menggantikan gambar/ilustrasi hero saat halaman dibuka. Kosongkan untuk memakai gambar/ilustrasi di bawah.</small>
        </div>

        <div class="field2 full">
          <label for="hero_image">Gambar Hero</label>
          @if ($settings->hero_image_url)
            <img src="{{ $settings->hero_image_url }}" alt="Gambar hero saat ini" style="max-width:320px;border-radius:12px;margin-bottom:12px;">
            <label><input type="checkbox" name="remove_hero_image" value="1" @checked(old('remove_hero_image'))> Hapus gambar dan gunakan ilustrasi bawaan</label>
          @endif
          <input id="hero_image" type="file" name="hero_image" accept="image/jpeg,image/png,image/webp">
          <small>JPG, PNG, atau WebP, maksimal 5 MB. Rasio yang disarankan 16:8. Diabaikan bila Video Hero di atas diisi.</small>
        </div>

        <div class="field2 full">
          <label for="about_title">Judul About Us</label>
          <input id="about_title" name="about_title" value="{{ old('about_title', $settings->about_title) }}" maxlength="255">
        </div>

        <div class="field2 full">
          <label for="about_description">Deskripsi About Us</label>
          <textarea id="about_description" name="about_description" maxlength="10000">{{ old('about_description', $settings->about_description) }}</textarea>
        </div>

        <div class="field2 full">
          <label for="map_query">Lokasi Google Maps</label>
          <input id="map_query" name="map_query" value="{{ old('map_query', $settings->map_query) }}" maxlength="255">
          <small>Isi nama tempat, alamat, atau koordinat. Jika kosong, peta menggunakan alamat kantor.</small>
        </div>

      </div>
    </div>
  </div>
</form>
@endsection
