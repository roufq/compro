@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
<form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  @include('admin.settings._collection-section', [
      'section' => 'products',
      'definition' => ['title' => 'Produk Digital / Download', 'fields' => ['name' => 'Nama produk', 'description' => 'Deskripsi', 'url' => 'URL produk / checkout']],
  ])
  <button type="submit" class="btn-primary">Simpan Perubahan</button>
</form>
@endsection
