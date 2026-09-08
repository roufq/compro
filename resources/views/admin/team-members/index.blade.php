@extends('layouts.admin')

@section('title', 'Tim Studio')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Tim Studio</h3>
      <p>{{ $teamMembers->count() }} anggota ditampilkan di halaman utama</p>
    </div>
    <button type="button" class="btn-primary" onclick="document.getElementById('member-add-modal').showModal()">+ Tambah Anggota</button>
  </div>

  @if ($teamMembers->isEmpty())
    <div class="empty-note">Belum ada anggota tim. Tambahkan lewat tombol di atas.</div>
  @else
    <table>
      <thead><tr><th>Anggota</th><th>Jabatan</th><th></th></tr></thead>
      <tbody>
        @foreach ($teamMembers as $member)
          <tr>
            <td>
              <div class="thumb-cell">
                <div class="thumb" style="@if($member->image_url) background-image:url('{{ $member->image_url }}'); @endif"></div>
                <div><strong>{{ $member->name }}</strong></div>
              </div>
            </td>
            <td>{{ $member->role }}</td>
            <td>
              <div class="row-actions">
                <button type="button" class="icon-btn" aria-label="Detail {{ $member->name }}" onclick="document.getElementById('member-detail-modal-{{ $member->id }}').showModal()">👁</button>
                <button type="button" class="icon-btn" aria-label="Edit {{ $member->name }}" onclick="document.getElementById('member-edit-modal-{{ $member->id }}').showModal()">✎</button>
                <form method="POST" action="{{ route('admin.tim.destroy', $member) }}" onsubmit="return confirm('Hapus anggota ini?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="icon-btn" aria-label="Hapus {{ $member->name }}">✕</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

<dialog class="admin-modal" id="member-add-modal" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
  <div class="admin-modal-head">
    <div><h3>Tambah Anggota Tim</h3><p>Akan tampil pada bagian "Tim di balik karya"</p></div>
    <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('member-add-modal').close()">×</button>
  </div>
  <div class="admin-modal-body">
    <form method="POST" action="{{ route('admin.tim.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-grid">
        <div class="field2">
          <label>Nama</label>
          <input type="text" name="name" required>
        </div>
        <div class="field2">
          <label>Jabatan</label>
          <input type="text" name="role" required>
        </div>
        <div class="field2">
          <label>Upload Foto</label>
          <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
        </div>
        <div class="field2">
          <label>atau URL Foto</label>
          <input type="url" name="image_url" placeholder="https://...">
        </div>
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="btn-outline" onclick="document.getElementById('member-add-modal').close()">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</dialog>

@foreach ($teamMembers as $member)
  <dialog class="admin-modal" id="member-edit-modal-{{ $member->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>Edit Anggota Tim</h3><p>Perbarui informasi {{ $member->name }}</p></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('member-edit-modal-{{ $member->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      <form method="POST" action="{{ route('admin.tim.update', $member) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-grid">
          <div class="field2">
            <label>Nama</label>
            <input type="text" name="name" value="{{ $member->name }}" required>
          </div>
          <div class="field2">
            <label>Jabatan</label>
            <input type="text" name="role" value="{{ $member->role }}" required>
          </div>
          @if ($member->image_url)
            <div class="field2 full">
              <div class="current-media">
                <img src="{{ $member->image_url }}" alt="{{ $member->name }}">
                <div><strong>{{ $member->name }}</strong><span>Foto saat ini</span></div>
              </div>
            </div>
          @endif
          <div class="field2">
            <label>Ganti Foto <span style="color:#9a9ca2;font-weight:400;">(opsional)</span></label>
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
          </div>
          <div class="field2">
            <label>atau URL Foto</label>
            <input type="url" name="image_url" value="{{ $member->image_url }}" placeholder="https://...">
          </div>
        </div>
        <div class="admin-modal-actions">
          <button type="button" class="btn-outline" onclick="document.getElementById('member-edit-modal-{{ $member->id }}').close()">Batal</button>
          <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </dialog>

  <dialog class="admin-modal" id="member-detail-modal-{{ $member->id }}" onclick="if(event.target===event.currentTarget) event.currentTarget.close()">
    <div class="admin-modal-head">
      <div><h3>Detail Anggota</h3></div>
      <button type="button" class="admin-modal-close" aria-label="Tutup modal" onclick="document.getElementById('member-detail-modal-{{ $member->id }}').close()">×</button>
    </div>
    <div class="admin-modal-body">
      <div class="current-media">
        @if ($member->image_url)
          <img src="{{ $member->image_url }}" alt="{{ $member->name }}">
        @endif
        <div><strong>{{ $member->name }}</strong><span>{{ $member->role }}</span></div>
      </div>
    </div>
  </dialog>
@endforeach

@endsection
