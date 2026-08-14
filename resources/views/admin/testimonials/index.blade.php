@extends('layouts.admin')

@section('title', 'Testimoni')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Tambah Testimoni</h3>
      <p>Akan tampil pada bagian "Testimoni" di halaman utama</p>
    </div>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.testimoni.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-grid">
        <div class="field2">
          <label>Nama</label>
          <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="field2">
          <label>Jabatan / Perusahaan</label>
          <input type="text" name="role" value="{{ old('role') }}">
        </div>
        <div class="field2 full">
          <label>Isi Testimoni</label>
          <textarea name="quote" required>{{ old('quote') }}</textarea>
        </div>
        <div class="field2">
          <label>Rating (1–5)</label>
          <input type="number" name="rating" min="1" max="5" value="{{ old('rating', 5) }}">
        </div>
        <div class="field2">
          <label>Foto (opsional)</label>
          <input type="file" name="avatar" accept="image/*">
        </div>
      </div>
      <div style="margin-top:16px;">
        <button type="submit" class="btn-primary">+ Tambah Testimoni</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <div>
      <h3>Daftar Testimoni</h3>
      <p>{{ $testimonials->count() }} testimoni</p>
    </div>
  </div>

  @if ($testimonials->isEmpty())
    <div class="empty-note">Belum ada testimoni. Tambahkan lewat form di atas.</div>
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
                <form method="POST" action="{{ route('admin.testimoni.destroy', $t) }}" onsubmit="return confirm('Hapus testimoni ini?');">
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
