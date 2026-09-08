@extends('layouts.admin')

@section('title', 'Testimoni')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Daftar Testimoni</h3>
      <p>{{ $testimonials->count() }} testimoni ditampilkan di halaman utama</p>
    </div>
    <button type="button" class="btn-primary" onclick="document.getElementById('testimonial-add-modal').showModal()">+ Tambah Testimoni</button>
  </div>

  @if ($testimonials->isEmpty())
    <div class="empty-note">Belum ada testimoni. Tambahkan lewat tombol di atas.</div>
  @else
    <table>
      <thead><tr><th>Nama</th><th>Testimoni</th><th>Rating</th><th></th></tr></thead>
      <tbody>
        @foreach ($testimonials as $t)
          <tr>
            <td><strong>{{ $t->name }}</strong><br><span style="color:#9a9ca2;font-size:11.5px;">{{ $t->role }}</span></td>
            <td>{{ Str::limit($t->quote, 60) }}</td>
            <td>{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</td>
            <td>
              <div class="row-actions">
                <button type="button" class="icon-btn" aria-label="Detail {{ $t->name }}" onclick="document.getElementById('testimonial-detail-modal-{{ $t->id }}').showModal()">👁</button>
                <button type="button" class="icon-btn" aria-label="Edit {{ $t->name }}" onclick="document.getElementById('testimonial-edit-modal-{{ $t->id }}').showModal()">✎</button>
                <form method="POST" action="{{ route('admin.testimoni.destroy', $t) }}" onsubmit="return confirm('Hapus testimoni ini?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="icon-btn" aria-label="Hapus {{ $t->name }}">✕</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

<dialog class="admin-modal" id="testimonial-add-modal" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
  <div class="admin-modal-head">
    <div><h3>Tambah Testimoni</h3><p>Akan tampil pada bagian "Testimoni" di halaman utama</p></div>
    <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('testimonial-add-modal').close()">×</button>
  </div>
  <div class="admin-modal-body">
    <form method="POST" action="{{ route('admin.testimoni.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-grid">
        <div class="field2">
          <label>Nama</label>
          <input type="text" name="name" required>
        </div>
        <div class="field2">
          <label>Jabatan / Perusahaan</label>
          <input type="text" name="role">
        </div>
        <div class="field2 full">
          <label>Isi Testimoni</label>
          <textarea name="quote" required></textarea>
        </div>
        <div class="field2">
          <label>Rating (1–5)</label>
          <input type="number" name="rating" min="1" max="5" value="5">
        </div>
        <div class="field2">
          <label>Foto (opsional)</label>
          <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp">
        </div>
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="btn-outline" onclick="document.getElementById('testimonial-add-modal').close()">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</dialog>

@foreach ($testimonials as $t)
  <dialog class="admin-modal" id="testimonial-edit-modal-{{ $t->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>Edit Testimoni</h3><p>Perbarui testimoni dari {{ $t->name }}</p></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('testimonial-edit-modal-{{ $t->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      <form method="POST" action="{{ route('admin.testimoni.update', $t) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-grid">
          <div class="field2">
            <label>Nama</label>
            <input type="text" name="name" value="{{ $t->name }}" required>
          </div>
          <div class="field2">
            <label>Jabatan / Perusahaan</label>
            <input type="text" name="role" value="{{ $t->role }}">
          </div>
          <div class="field2 full">
            <label>Isi Testimoni</label>
            <textarea name="quote" required>{{ $t->quote }}</textarea>
          </div>
          <div class="field2">
            <label>Rating (1–5)</label>
            <input type="number" name="rating" min="1" max="5" value="{{ $t->rating }}">
          </div>
          @if ($t->avatar_url)
            <div class="field2 full">
              <div class="current-media">
                <img src="{{ $t->avatar_url }}" alt="{{ $t->name }}">
                <div><strong>{{ $t->name }}</strong><span>Foto saat ini</span></div>
              </div>
            </div>
          @endif
          <div class="field2">
            <label>Ganti Foto <span style="color:#9a9ca2;font-weight:400;">(opsional)</span></label>
            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp">
          </div>
        </div>
        <div class="admin-modal-actions">
          <button type="button" class="btn-outline" onclick="document.getElementById('testimonial-edit-modal-{{ $t->id }}').close()">Batal</button>
          <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </dialog>

  <dialog class="admin-modal" id="testimonial-detail-modal-{{ $t->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>{{ $t->name }}</h3><p>{{ $t->role }}</p></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('testimonial-detail-modal-{{ $t->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      <div class="current-media" style="margin-bottom:14px;">
        @if ($t->avatar_url)
          <img src="{{ $t->avatar_url }}" alt="{{ $t->name }}">
        @endif
        <div><span>{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</span></div>
      </div>
      <p style="color:var(--text-muted);white-space:pre-line;">"{{ $t->quote }}"</p>
    </div>
  </dialog>
@endforeach

@endsection
