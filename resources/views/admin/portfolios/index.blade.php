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
          <input type="text" name="category" value="{{ old('category') }}" list="portfolioCategories" placeholder="Pilih atau ketik kategori baru" autocomplete="off" required>
          <datalist id="portfolioCategories">
            @foreach ($categories as $category)
              <option value="{{ $category }}">{{ Str::headline($category) }}</option>
            @endforeach
          </datalist>
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
                <button type="button" class="icon-btn" data-test="edit-portfolio-{{ $item->id }}" aria-label="Edit {{ $item->title }}" onclick="openPortfolioModal({{ $item->id }})">✎</button>
                <form method="POST" action="{{ route('admin.portofolio.destroy', $item) }}" onsubmit="return confirm('Hapus karya ini?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="icon-btn" aria-label="Hapus {{ $item->title }}">✕</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

@foreach ($portfolios as $item)
  <dialog class="admin-modal" id="portfolio-modal-{{ $item->id }}" data-test="portfolio-edit-modal-{{ $item->id }}" onclick="closeModalFromBackdrop(event)">
    <div class="admin-modal-head">
      <div>
        <h3>Edit Portofolio</h3>
        <p>Perbarui informasi dan media untuk {{ $item->title }}</p>
      </div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="closePortfolioModal({{ $item->id }})">×</button>
    </div>
    <div class="admin-modal-body">
      <form id="portfolio-edit-form-{{ $item->id }}" method="POST" action="{{ route('admin.portofolio.update', $item) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-grid">
          <div class="field2">
            <label>Judul Karya</label>
            <input type="text" name="title" value="{{ $item->title }}" required>
          </div>
          <div class="field2">
            <label>Kategori</label>
            <input type="text" name="category" value="{{ $item->category }}" list="portfolioCategories" placeholder="Pilih atau ketik kategori baru" autocomplete="off" required>
          </div>
          <div class="field2">
            <label>Tipe Karya</label>
            <select name="type" id="editType-{{ $item->id }}" onchange="toggleEditType({{ $item->id }})">
              <option value="gambar" @selected($item->type === 'gambar')>Gambar (upload file)</option>
              <option value="video" @selected($item->type === 'video')>Video (link YouTube)</option>
            </select>
          </div>
          <div class="field2">
            <label>Urutan Tampil</label>
            <input type="number" name="order" min="0" value="{{ $item->order }}">
          </div>
          @if ($item->thumbnail_url)
            <div class="field2 full">
              <label>Media Saat Ini</label>
              <div class="current-media">
                <img id="portfolio-preview-{{ $item->id }}" src="{{ $item->thumbnail_url }}" data-original-src="{{ $item->thumbnail_url }}" alt="Media saat ini untuk {{ $item->title }}">
                <div>
                  <strong>{{ $item->title }}</strong>
                  <span>{{ $item->isVideo() ? 'Thumbnail video YouTube yang sedang digunakan' : 'Gambar yang sedang digunakan' }}</span>
                </div>
              </div>
            </div>
          @endif
          <div class="field2" id="editImage-{{ $item->id }}">
            <label>Ganti Gambar <span style="color:#9a9ca2;font-weight:400;">(opsional)</span></label>
            <input type="file" name="image" accept="image/*" onchange="previewPortfolioImage({{ $item->id }}, this)">
          </div>
          <div class="field2" id="editVideo-{{ $item->id }}">
            <label>Link YouTube</label>
            <input type="url" name="youtube_url" value="{{ $item->youtube_url }}" placeholder="https://youtu.be/xxxxxxxxxxx">
          </div>
          <div class="field2 full">
            <label>Deskripsi</label>
            <textarea name="description">{{ $item->description }}</textarea>
          </div>
        </div>
        <div class="admin-modal-actions">
          <button type="button" class="btn-outline" onclick="closePortfolioModal({{ $item->id }})">Batal</button>
          <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </dialog>
@endforeach

@endsection

@push('scripts')
<script>
  function toggleType(){
    const type = document.getElementById('typeSelect').value;
    document.getElementById('fieldGambar').style.display = type === 'gambar' ? 'block' : 'none';
    document.getElementById('fieldVideo').style.display = type === 'video' ? 'block' : 'none';
  }

  function openPortfolioModal(id){
    toggleEditType(id);
    document.getElementById(`portfolio-modal-${id}`).showModal();
  }

  function closePortfolioModal(id){
    document.getElementById(`portfolio-modal-${id}`).close();
  }

  function closeModalFromBackdrop(event){
    if (event.target === event.currentTarget) {
      event.currentTarget.close();
    }
  }

  function toggleEditType(id){
    const type = document.getElementById(`editType-${id}`).value;
    document.getElementById(`editImage-${id}`).style.display = type === 'gambar' ? 'block' : 'none';
    document.getElementById(`editVideo-${id}`).style.display = type === 'video' ? 'block' : 'none';
  }

  function previewPortfolioImage(id, input){
    const preview = document.getElementById(`portfolio-preview-${id}`);
    const file = input.files[0];

    if (preview && file) {
      preview.src = URL.createObjectURL(file);
    }
  }

  toggleType();
</script>
@endpush
