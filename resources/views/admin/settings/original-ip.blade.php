@extends('layouts.admin')

@section('title', 'Original IP')

@section('content')
<form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  @include('admin.settings._collection-section', [
      'section' => 'original_ips',
      'definition' => ['title' => 'Original IP', 'fields' => ['name' => 'Nama IP', 'description' => 'Deskripsi Singkat', 'image_url' => 'Logo / sampul (opsional)', 'url' => 'Tautan eksternal tambahan (opsional)']],
  ])
  <button type="submit" class="btn-primary">Simpan Perubahan</button>
</form>
@endsection
