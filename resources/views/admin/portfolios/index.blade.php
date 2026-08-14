@extends('layouts.admin')

@section('title', 'Portofolio')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Tambah Karya Baru</h3>
      <p>Pilih tipe "Gambar" untuk upload file, atau "Video" cukup tempel link YouTube</p>
    </div>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.portofolio.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-grid">
        <div class="field2">
          <label>Judul Karya</label>
          <input type="text" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="field2">
          <label>Kategori</label>
          <input type="text" name="category" value="{{ old('category') }}" placeholder="branding / produk / dll" required>
        </div>

        <div class="field2">
          <label>Tipe Karya</label>
          <select name="type" id="typeSelect" onchange="toggleType()">
            <option value="gambar" @selected(old('type', 'gambar') === 'gambar')>Gambar (upload file)</option>
            <option value="video" @selected(old('type') === 'video')>Video (link YouTube)</option>
          </select>
        </div>
        <div class="field2">
          <label>Urutan Tampil</label>
          <input type="number" name="order" min="0" value="{{ old('order', 0) }}">
        </div>

        <div class="field2" id="fieldGambar">
          <label>Upload Gambar</label>
          <input type="file" name="image" accept="image/*">
        </div>
        <div class="field2" id="fieldVideo" style="display:none;">
          <label>Link YouTube</label>
          <input type="url" name="youtube_url" value="{{ old('youtube_url') }}" placeholder="https://youtu.be/xxxxxxxxxxx">
        </div>

        <div class="field2 full">
          <label>Deskripsi</label>
          <textarea name="description">{{ old('description') }}</textarea>
        </div>
      </div>
      <div style="margin-top:16px;">
        <button type="submit" class="btn-primary">+ Tambah Karya</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <div>
      <h3>Galeri Portofolio</h3>
      <p>{{ $portfolios->count() }} karya ditampilkan di halaman utama</p>
    </div>
  </div>

  @if ($portfolios->isEmpty())
    <div class="empty-note">Belum ada karya. Tambahkan lewat form di atas.</div>
  @else
    <table>
      <thead><tr><th>Karya</th><th>Tipe</th><th>Kategori</th><th></th></tr></thead>
      <tbody>
        @foreach ($portfolios as $item)
          <tr>
            <td>
              <div class="thumb-cell">
                <div class="thumb" style="@if($item->thumbnail_url) background-image:url('{{ $item->thumbnail_url }}'); @endif"></div>
                <div>
                  <strong>{{ $item->title }}</strong>
                  <span>{{ $item->isVideo() ? $item->youtube_url : 'File gambar' }}</span>
                </div>
              </div>
            </td>
            <td><span class="badge {{ $item->type }}">{{ ucfirst($item->type) }}</span></td>
            <td>{{ ucfirst($item->category) }}</td>
            <td>
              <div class="row-actions">
                <form method="POST" action="{{ route('admin.portofolio.destroy', $item) }}" onsubmit="return confirm('Hapus karya ini?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="icon-btn">✕</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

@endsection

@push('scripts')
<script>
  function toggleType(){
    const type = document.getElementById('typeSelect').value;
    document.getElementById('fieldGambar').style.display = type === 'gambar' ? 'block' : 'none';
    document.getElementById('fieldVideo').style.display = type === 'video' ? 'block' : 'none';
  }
  toggleType();
</script>
@endpush
