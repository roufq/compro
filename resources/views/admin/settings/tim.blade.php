@extends('layouts.admin')

@section('title', 'Tim Studio')

@section('content')
<form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  @include('admin.settings._collection-section', [
      'section' => 'team_members',
      'definition' => ['title' => 'Tim Studio', 'fields' => ['name' => 'Nama anggota', 'role' => 'Jabatan', 'image_url' => 'Foto anggota (opsional)']],
  ])
  <button type="submit" class="btn-primary">Simpan Perubahan</button>
</form>
@endsection
