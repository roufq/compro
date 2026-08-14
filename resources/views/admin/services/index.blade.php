@extends('layouts.admin')

@section('title', 'Layanan')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Tambah Layanan Baru</h3>
      <p>Akan tampil pada bagian "Layanan" di halaman utama</p>
    </div>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.layanan.store') }}">
      @csrf
      <div class="form-grid">
        <div class="field2">
          <label>Judul Layanan</label>
          <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Text-to-Image" required>
        </div>
        <div class="field2">
          <label>Urutan Tampil</label>
          <input type="number" name="order" min="0" value="{{ old('order', 0) }}">
        </div>
        <div class="field2 full">
          <label>Deskripsi</label>
          <textarea name="description" required>{{ old('description') }}</textarea>
        </div>
      </div>
      <div style="margin-top:16px;">
        <button type="submit" class="btn-primary">+ Tambah Layanan</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <div>
      <h3>Daftar Layanan</h3>
      <p>{{ $services->count() }} layanan aktif</p>
    </div>
  </div>

  @if ($services->isEmpty())
    <div class="empty-note">Belum ada layanan. Tambahkan lewat form di atas.</div>
  @else
    <table>
      <thead><tr><th>Layanan</th><th>Deskripsi</th><th></th></tr></thead>
      <tbody>
        @foreach ($services as $service)
          <tr>
            <td><strong>{{ $service->title }}</strong></td>
            <td>{{ Str::limit($service->description, 70) }}</td>
            <td>
              <div class="row-actions">
                <button type="button" class="icon-btn" onclick="document.getElementById('edit-{{ $service->id }}').style.display='table-row'; this.closest('tr').style.display='none';">✎</button>
                <form method="POST" action="{{ route('admin.layanan.destroy', $service) }}" onsubmit="return confirm('Hapus layanan ini?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="icon-btn">✕</button>
                </form>
              </div>
            </td>
          </tr>
          <tr id="edit-{{ $service->id }}" style="display:none;background:#f5f5f7;">
            <td colspan="3">
              <form method="POST" action="{{ route('admin.layanan.update', $service) }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                @csrf @method('PUT')
                <div class="field2" style="flex:1;min-width:160px;">
                  <label>Judul</label>
                  <input type="text" name="title" value="{{ $service->title }}" required>
                </div>
                <div class="field2" style="flex:2;min-width:220px;">
                  <label>Deskripsi</label>
                  <input type="text" name="description" value="{{ $service->description }}" required>
                </div>
                <div class="field2" style="width:90px;">
                  <label>Urutan</label>
                  <input type="number" name="order" value="{{ $service->order }}">
                </div>
                <button type="submit" class="btn-primary">Simpan</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

@endsection
