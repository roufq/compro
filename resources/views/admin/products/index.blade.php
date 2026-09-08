@extends('layouts.admin')

@section('title', 'Produk')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Produk Digital / Download</h3>
      <p>{{ $products->count() }} produk ditampilkan di halaman utama</p>
    </div>
    <button type="button" class="btn-primary" onclick="document.getElementById('product-add-modal').showModal()">+ Tambah Produk</button>
  </div>

  @if ($products->isEmpty())
    <div class="empty-note">Belum ada produk. Tambahkan lewat tombol di atas.</div>
  @else
    <table>
      <thead><tr><th>Produk</th><th>Deskripsi</th><th></th></tr></thead>
      <tbody>
        @foreach ($products as $product)
          <tr>
            <td><strong>{{ $product->name }}</strong></td>
            <td>{{ Str::limit($product->description, 70) }}</td>
            <td>
              <div class="row-actions">
                <button type="button" class="icon-btn" aria-label="Detail {{ $product->name }}" onclick="document.getElementById('product-detail-modal-{{ $product->id }}').showModal()">👁</button>
                <button type="button" class="icon-btn" aria-label="Edit {{ $product->name }}" onclick="document.getElementById('product-edit-modal-{{ $product->id }}').showModal()">✎</button>
                <form method="POST" action="{{ route('admin.produk.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="icon-btn" aria-label="Hapus {{ $product->name }}">✕</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

<dialog class="admin-modal" id="product-add-modal" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
  <div class="admin-modal-head">
    <div><h3>Tambah Produk</h3><p>Akan tampil pada bagian "Produk Digital"</p></div>
    <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('product-add-modal').close()">×</button>
  </div>
  <div class="admin-modal-body">
    <form method="POST" action="{{ route('admin.produk.store') }}">
      @csrf
      <div class="form-grid">
        <div class="field2 full">
          <label>Nama Produk</label>
          <input type="text" name="name" required>
        </div>
        <div class="field2 full">
          <label>Deskripsi</label>
          <textarea name="description"></textarea>
        </div>
        <div class="field2 full">
          <label>URL Produk / Checkout</label>
          <input type="url" name="url" placeholder="https://..." required>
        </div>
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="btn-outline" onclick="document.getElementById('product-add-modal').close()">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</dialog>

@foreach ($products as $product)
  <dialog class="admin-modal" id="product-edit-modal-{{ $product->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>Edit Produk</h3><p>Perbarui informasi {{ $product->name }}</p></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('product-edit-modal-{{ $product->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      <form method="POST" action="{{ route('admin.produk.update', $product) }}">
        @csrf @method('PUT')
        <div class="form-grid">
          <div class="field2 full">
            <label>Nama Produk</label>
            <input type="text" name="name" value="{{ $product->name }}" required>
          </div>
          <div class="field2 full">
            <label>Deskripsi</label>
            <textarea name="description">{{ $product->description }}</textarea>
          </div>
          <div class="field2 full">
            <label>URL Produk / Checkout</label>
            <input type="url" name="url" value="{{ $product->url }}" required>
          </div>
        </div>
        <div class="admin-modal-actions">
          <button type="button" class="btn-outline" onclick="document.getElementById('product-edit-modal-{{ $product->id }}').close()">Batal</button>
          <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </dialog>

  <dialog class="admin-modal" id="product-detail-modal-{{ $product->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>{{ $product->name }}</h3></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('product-detail-modal-{{ $product->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      <p style="color:var(--text-muted);white-space:pre-line;">{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>
      <p style="margin-top:12px;"><a class="btn-outline" href="{{ $product->url }}" target="_blank" rel="noopener noreferrer">Buka URL Produk ↗</a></p>
    </div>
  </dialog>
@endforeach

@endsection
