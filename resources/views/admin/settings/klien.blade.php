@extends('layouts.admin')

@section('title', 'Klien')

@section('content')
<form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  @include('admin.settings._collection-section', [
      'section' => 'clients',
      'definition' => ['title' => 'Klien / Trusted By', 'fields' => ['name' => 'Nama klien', 'image_url' => 'Logo klien (opsional)']],
  ])
  <button type="submit" class="btn-primary">Simpan Perubahan</button>
</form>
@endsection
