@extends('layouts.admin')

@section('title', 'Original IP')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Original IP</h3>
      <p>{{ $originalIps->count() }} IP ditampilkan di halaman utama</p>
    </div>
    <button type="button" class="btn-primary" onclick="document.getElementById('ip-add-modal').showModal()">+ Tambah Original IP</button>
  </div>

  @if ($originalIps->isEmpty())
    <div class="empty-note">Belum ada Original IP. Tambahkan lewat tombol di atas.</div>
  @else
    <table>
      <thead><tr><th>Original IP</th><th>Channel</th><th></th></tr></thead>
      <tbody>
        @foreach ($originalIps as $ip)
          <tr>
            <td>
              <div class="thumb-cell">
                <div class="thumb" style="@if($ip->image_url) background-image:url('{{ $ip->image_url }}'); @endif"></div>
                <div>
                  <strong>{{ $ip->name }}</strong>
                  <span>{{ Str::limit($ip->description, 50) }}</span>
                </div>
              </div>
            </td>
            <td>{{ $ip->url ? 'Ada link' : '—' }}</td>
            <td>
              <div class="row-actions">
                <button type="button" class="icon-btn" aria-label="Detail {{ $ip->name }}" onclick="document.getElementById('ip-detail-modal-{{ $ip->id }}').showModal()">👁</button>
                <button type="button" class="icon-btn" aria-label="Edit {{ $ip->name }}" onclick="document.getElementById('ip-edit-modal-{{ $ip->id }}').showModal()">✎</button>
                <form method="POST" action="{{ route('admin.original-ip.destroy', $ip) }}" onsubmit="return confirm('Hapus Original IP ini?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="icon-btn" aria-label="Hapus {{ $ip->name }}">✕</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

<dialog class="admin-modal" id="ip-add-modal" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
  <div class="admin-modal-head">
    <div><h3>Tambah Original IP</h3><p>Halaman detail dibuat otomatis dari nama IP</p></div>
    <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('ip-add-modal').close()">×</button>
  </div>
  <div class="admin-modal-body">
    <form method="POST" action="{{ route('admin.original-ip.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-grid">
        <div class="field2 full">
          <label>Nama IP</label>
          <input type="text" name="name" required>
        </div>
        <div class="field2 full">
          <label>Deskripsi Singkat</label>
          <input type="text" name="description">
        </div>
        <div class="field2">
          <label>Upload Sampul</label>
          <input type="file" name="cover" accept="image/jpeg,image/png,image/webp">
        </div>
        <div class="field2">
          <label>atau URL Sampul</label>
          <input type="url" name="image_url" placeholder="https://...">
        </div>
        <div class="field2 full">
          <label>Link Channel YouTube <span style="color:#9a9ca2;font-weight:400;">(opsional)</span></label>
          <input type="url" name="url" placeholder="https://youtube.com/@channel">
        </div>
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="btn-outline" onclick="document.getElementById('ip-add-modal').close()">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</dialog>

@foreach ($originalIps as $ip)
  <dialog class="admin-modal" id="ip-edit-modal-{{ $ip->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>Edit Original IP</h3><p>Perbarui informasi {{ $ip->name }}</p></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('ip-edit-modal-{{ $ip->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      <form method="POST" action="{{ route('admin.original-ip.update', $ip) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-grid">
          <div class="field2 full">
            <label>Nama IP</label>
            <input type="text" name="name" value="{{ $ip->name }}" required>
          </div>
          <div class="field2 full">
            <label>Deskripsi Singkat</label>
            <input type="text" name="description" value="{{ $ip->description }}">
          </div>
          @if ($ip->image_url)
            <div class="field2 full">
              <div class="current-media">
                <img src="{{ $ip->image_url }}" alt="{{ $ip->name }}">
                <div><strong>{{ $ip->name }}</strong><span>Sampul saat ini</span></div>
              </div>
            </div>
          @endif
          <div class="field2">
            <label>Ganti Sampul <span style="color:#9a9ca2;font-weight:400;">(opsional)</span></label>
            <input type="file" name="cover" accept="image/jpeg,image/png,image/webp">
          </div>
          <div class="field2">
            <label>atau URL Sampul</label>
            <input type="url" name="image_url" value="{{ $ip->image_url }}" placeholder="https://...">
          </div>
          <div class="field2 full">
            <label>Link Channel YouTube <span style="color:#9a9ca2;font-weight:400;">(opsional)</span></label>
            <input type="url" name="url" value="{{ $ip->url }}" placeholder="https://youtube.com/@channel">
          </div>
        </div>
        <div class="admin-modal-actions">
          <button type="button" class="btn-outline" onclick="document.getElementById('ip-edit-modal-{{ $ip->id }}').close()">Batal</button>
          <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </dialog>

  <dialog class="admin-modal" id="ip-detail-modal-{{ $ip->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>{{ $ip->name }}</h3><p>{{ $ip->description }}</p></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('ip-detail-modal-{{ $ip->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      @if ($ip->image_url)
        <div class="current-media" style="margin-bottom:14px;">
          <img src="{{ $ip->image_url }}" alt="{{ $ip->name }}">
          <div><span>Slug: {{ $ip->slug }}</span></div>
        </div>
      @endif
      <p style="color:var(--text-muted);white-space:pre-line;">{{ $ip->details ?: 'Belum ada informasi lengkap.' }}</p>
      <p style="margin-top:12px;color:var(--text-faint);font-size:12.5px;">{{ count($ip->photos) }} foto galeri · {{ $ip->video_ids->count() }} video</p>
    </div>
  </dialog>
@endforeach

@endsection
