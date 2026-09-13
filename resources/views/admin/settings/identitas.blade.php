@extends('layouts.admin')

@section('title', 'Identitas')

@section('content')
<form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="card">
    <div class="card-head">
      <div>
        <h3>Identitas Perusahaan</h3>
        <p>Logo, nama, dan tagline yang tampil di header serta footer situs.</p>
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

        <div class="field2 full">
          <label>Gambar Teks Logo (Wordmark)</label>
          <div style="display:flex;align-items:center;gap:14px;">
            <div style="min-width:160px;height:56px;border-radius:14px;background:#f1effd;display:flex;align-items:center;justify-content:center;border:1px dashed #6c5ce7;overflow:hidden;padding:4px 10px;">
              @if ($settings->logo_text_url)
                <img src="{{ $settings->logo_text_url }}" alt="Teks Logo" style="max-width:100%;max-height:100%;object-fit:contain;">
              @else
                <span style="font-size:12px;color:#8a86b8;">Belum ada gambar</span>
              @endif
            </div>
            <label class="btn-outline" style="cursor:pointer;">
              Ganti Gambar
              <input type="file" name="logo_text" accept="image/*" style="display:none;">
            </label>
            @if ($settings->logo_text_url)
              <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#5A6785;">
                <input type="checkbox" name="remove_logo_text" value="1"> Hapus
              </label>
            @endif
          </div>
          <p style="font-size:12px;color:#8a86b8;margin-top:6px;">Opsional. Jika diisi, gambar ini menggantikan tampilan teks nama perusahaan &amp; tagline di sebelah logo pada header situs.</p>
        </div>

        <div class="field2">
          <label>Nama Perusahaan</label>
          <input type="text" name="company_name" value="{{ old('company_name', $settings->company_name) }}" required>
        </div>

        <div class="field2">
          <label>Tagline</label>
          <input type="text" name="tagline" value="{{ old('tagline', $settings->tagline) }}">
        </div>

      </div>
    </div>
  </div>
</form>
@endsection
