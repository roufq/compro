@extends('layouts.admin')

@section('title', 'Portofolio')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Galeri Portofolio</h3>
      <p>{{ $portfolios->count() }} karya ditampilkan di halaman utama</p>
    </div>
    <button type="button" class="btn-primary" onclick="document.getElementById('portfolio-add-modal').showModal()">+ Tambah Karya</button>
  </div>

  @if ($portfolios->isEmpty())
    <div class="empty-note">Belum ada karya. Tambahkan lewat tombol di atas.</div>
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
                <button type="button" class="icon-btn" aria-label="Detail {{ $item->title }}" onclick="openPortfolioModal('detail', {{ $item->id }})">👁</button>
                <button type="button" class="icon-btn" data-test="edit-portfolio-{{ $item->id }}" aria-label="Edit {{ $item->title }}" onclick="openPortfolioModal('edit', {{ $item->id }})">✎</button>
                <form method="POST" action="{{ route('admin.portofolio.destroy', $item) }}">
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

<dialog class="admin-modal" id="portfolio-add-modal" onclick="closeModalFromBackdrop(event)">
  <div class="admin-modal-head">
    <div>
      <h3>Tambah Karya Baru</h3>
      <p>Upload thumbnail untuk kartu portofolio dan tempel link video YouTube untuk modal</p>
    </div>
    <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('portfolio-add-modal').close()">×</button>
  </div>
  <div class="admin-modal-body">
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
          <label>Urutan Tampil</label>
          <input type="number" name="order" min="0" value="{{ old('order', 0) }}">
        </div>

        <div class="field2">
          <label>Thumbnail <span style="color:#9a9ca2;font-weight:400;">(opsional)</span></label>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-max-kb="250" data-file-label="Thumbnail portofolio">
          <small>Jika kosong, thumbnail YouTube akan digunakan.</small>
        </div>
        <div class="field2">
          <label>Link Video YouTube</label>
          <input type="url" name="youtube_url" value="{{ old('youtube_url') }}" placeholder="https://youtu.be/xxxxxxxxxxx" required>
        </div>

        <div class="field2 full">
          <label>Deskripsi</label>
          <textarea name="description">{{ old('description') }}</textarea>
        </div>
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="btn-outline" onclick="document.getElementById('portfolio-add-modal').close()">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</dialog>

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
            <input type="text" name="category" value="{{ $item->category }}" required>
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
                  <span>Thumbnail kartu yang sedang digunakan</span>
                </div>
              </div>
            </div>
          @endif
          <div class="field2">
            <label>Ganti Thumbnail <span style="color:#9a9ca2;font-weight:400;">(opsional)</span></label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-max-kb="250" data-file-label="Thumbnail portofolio" onchange="previewPortfolioImage({{ $item->id }}, this)">
          </div>
          <div class="field2">
            <label>Link Video YouTube</label>
            <input type="url" name="youtube_url" value="{{ $item->youtube_url }}" placeholder="https://youtu.be/xxxxxxxxxxx" required>
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

  <dialog class="admin-modal" id="portfolio-detail-modal-{{ $item->id }}" data-portfolio-detail-modal onclick="closeModalFromBackdrop(event)">
    <div class="admin-modal-head">
      <div><h3>{{ $item->title }}</h3><p>{{ ucfirst($item->category) }} · {{ ucfirst($item->type) }}</p></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="closePortfolioDetailModal({{ $item->id }})">×</button>
    </div>
    <div class="admin-modal-body">
      @if ($item->youtube_embed)
        <div style="aspect-ratio:16/9;border-radius:12px;overflow:hidden;margin-bottom:14px;">
          <iframe data-video-src="{{ $item->youtube_embed }}" title="Video {{ $item->title }}" style="width:100%;height:100%;border:0;" allowfullscreen loading="lazy"></iframe>
        </div>
      @elseif ($item->thumbnail_url)
        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" style="width:100%;border-radius:12px;margin-bottom:14px;">
      @endif
      <p style="color:var(--text-muted);white-space:pre-line;">{{ $item->description ?: 'Tidak ada deskripsi.' }}</p>
    </div>
  </dialog>
@endforeach

@endsection

@push('scripts')
<script>
  function openPortfolioModal(kind, id){
    if (kind === 'edit') {
      document.getElementById(`portfolio-modal-${id}`).showModal();
    } else {
      const dialog = document.getElementById(`portfolio-detail-modal-${id}`);
      const video = dialog.querySelector('[data-video-src]');

      if (video && ! video.hasAttribute('src')) {
        video.src = video.dataset.videoSrc;
      }

      dialog.showModal();
    }
  }

  function closePortfolioModal(id){
    document.getElementById(`portfolio-modal-${id}`).close();
  }

  function stopPortfolioVideo(dialog){
    const video = dialog.querySelector('[data-video-src]');

    if (video) {
      video.removeAttribute('src');
    }
  }

  function closePortfolioDetailModal(id){
    document.getElementById(`portfolio-detail-modal-${id}`).close();
  }

  function closeModalFromBackdrop(event){
    if (event.target === event.currentTarget) {
      event.currentTarget.close();
    }
  }

  function previewPortfolioImage(id, input){
    const preview = document.getElementById(`portfolio-preview-${id}`);
    const file = input.files[0];

    if (preview && file) {
      preview.src = URL.createObjectURL(file);
    }
  }

  document.querySelectorAll('[data-portfolio-detail-modal]').forEach((dialog) => {
    dialog.addEventListener('close', () => stopPortfolioVideo(dialog));
  });

</script>
@endpush
