@extends('layouts.admin')

@section('title', 'Kontak')

@section('content')
<form method="POST" action="{{ route('admin.pengaturan.update') }}">
  @csrf
  @method('PUT')

  <div class="card">
    <div class="card-head">
      <div>
        <h3>Kontak & Sosial Media</h3>
        <p>Tombol hubungi kami dan tautan sosial media yang tampil di situs.</p>
      </div>
      <button type="submit" class="btn-primary">Simpan Perubahan</button>
    </div>
    <div class="card-body">
      <div class="form-grid">

        <div class="field2">
          <label>Email Tombol Hubungi Kami</label>
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
