@extends('layouts.admin')

@section('title', 'Akun Saya')

@section('content')

<div class="card">
  <div class="card-head">
    <div>
      <h3>Informasi Akun</h3>
      <p>Nama ini tampil di pojok kiri bawah panel admin</p>
    </div>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.akun.profil.update') }}">
      @csrf
      @method('PUT')
      <div class="form-grid">
        <div class="field2">
          <label>Nama</label>
          <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
        </div>
        <div class="field2">
          <label>Email</label>
          <input type="email" value="{{ auth()->user()->email }}" disabled style="opacity:.6;">
        </div>
      </div>
      <div style="margin-top:16px;">
        <button type="submit" class="btn-primary">Simpan Nama</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <div>
      <h3>Ubah Password</h3>
      <p>Gunakan password baru yang kuat dan belum pernah dipakai di tempat lain</p>
    </div>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.akun.password.update') }}">
      @csrf
      @method('PUT')
      <div class="form-grid">
        <div class="field2 full">
          <label>Password Saat Ini</label>
          <input type="password" name="current_password" required autocomplete="current-password">
        </div>
        <div class="field2">
          <label>Password Baru</label>
          <input type="password" name="password" required autocomplete="new-password">
        </div>
        <div class="field2">
          <label>Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation" required autocomplete="new-password">
        </div>
      </div>
      <div style="margin-top:16px;">
        <button type="submit" class="btn-primary">Simpan Password</button>
      </div>
    </form>
  </div>
</div>

@endsection
