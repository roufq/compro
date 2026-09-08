@extends('layouts.admin')

@section('title', 'Klien')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Klien / Trusted By</h3>
      <p>{{ $clients->count() }} klien ditampilkan di halaman utama</p>
    </div>
    <button type="button" class="btn-primary" onclick="document.getElementById('client-add-modal').showModal()">+ Tambah Klien</button>
  </div>

  @if ($clients->isEmpty())
    <div class="empty-note">Belum ada klien. Tambahkan lewat tombol di atas.</div>
  @else
    <table>
      <thead><tr><th>Klien</th><th></th></tr></thead>
      <tbody>
        @foreach ($clients as $client)
          <tr>
            <td>
              <div class="thumb-cell">
                <div class="thumb" style="@if($client->image_url) background-image:url('{{ $client->image_url }}'); @endif"></div>
                <div><strong>{{ $client->name }}</strong></div>
              </div>
            </td>
            <td>
              <div class="row-actions">
                <button type="button" class="icon-btn" aria-label="Detail {{ $client->name }}" onclick="document.getElementById('client-detail-modal-{{ $client->id }}').showModal()">👁</button>
                <button type="button" class="icon-btn" aria-label="Edit {{ $client->name }}" onclick="document.getElementById('client-edit-modal-{{ $client->id }}').showModal()">✎</button>
                <form method="POST" action="{{ route('admin.klien.destroy', $client) }}" onsubmit="return confirm('Hapus klien ini?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="icon-btn" aria-label="Hapus {{ $client->name }}">✕</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

<dialog class="admin-modal" id="client-add-modal" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
  <div class="admin-modal-head">
    <div><h3>Tambah Klien</h3><p>Klien baru akan tampil di bagian "Dipercaya oleh"</p></div>
    <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('client-add-modal').close()">×</button>
  </div>
  <div class="admin-modal-body">
    <form method="POST" action="{{ route('admin.klien.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-grid">
        <div class="field2 full">
          <label>Nama Klien</label>
          <input type="text" name="name" required>
        </div>
        <div class="field2">
          <label>Upload Logo</label>
          <input type="file" name="logo" accept="image/jpeg,image/png,image/webp">
        </div>
        <div class="field2">
          <label>atau URL Logo</label>
          <input type="url" name="image_url" placeholder="https://...">
        </div>
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="btn-outline" onclick="document.getElementById('client-add-modal').close()">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</dialog>

@foreach ($clients as $client)
  <dialog class="admin-modal" id="client-edit-modal-{{ $client->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>Edit Klien</h3><p>Perbarui informasi {{ $client->name }}</p></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('client-edit-modal-{{ $client->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      <form method="POST" action="{{ route('admin.klien.update', $client) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-grid">
          <div class="field2 full">
            <label>Nama Klien</label>
            <input type="text" name="name" value="{{ $client->name }}" required>
          </div>
          @if ($client->image_url)
            <div class="field2 full">
              <div class="current-media">
                <img src="{{ $client->image_url }}" alt="{{ $client->name }}">
                <div><strong>{{ $client->name }}</strong><span>Logo saat ini</span></div>
              </div>
            </div>
          @endif
          <div class="field2">
            <label>Ganti Logo <span style="color:#9a9ca2;font-weight:400;">(opsional)</span></label>
            <input type="file" name="logo" accept="image/jpeg,image/png,image/webp">
          </div>
          <div class="field2">
            <label>atau URL Logo</label>
            <input type="url" name="image_url" value="{{ $client->image_url }}" placeholder="https://...">
          </div>
        </div>
        <div class="admin-modal-actions">
          <button type="button" class="btn-outline" onclick="document.getElementById('client-edit-modal-{{ $client->id }}').close()">Batal</button>
          <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </dialog>

  <dialog class="admin-modal" id="client-detail-modal-{{ $client->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>Detail Klien</h3></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('client-detail-modal-{{ $client->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      <div class="current-media">
        @if ($client->image_url)
          <img src="{{ $client->image_url }}" alt="{{ $client->name }}">
        @endif
        <div><strong>{{ $client->name }}</strong><span>Ditambahkan {{ $client->created_at?->translatedFormat('d M Y') }}</span></div>
      </div>
    </div>
  </dialog>
@endforeach

@endsection
