@props(['item' => [], 'index' => '__INDEX__'])

<input type="hidden" data-field="slug" name="original_ips[{{ $index }}][slug]" value="{{ $item['slug'] ?? '' }}">
<div class="field2 full">
  <label>Informasi Lengkap
    <textarea data-field="details" name="original_ips[{{ $index }}][details]" rows="7" maxlength="20000">{{ $item['details'] ?? '' }}</textarea>
  </label>
  <small>Ceritakan konsep, karakter, cerita, atau informasi lain mengenai IP ini.</small>
</div>
<div class="field2 full">
  <label>Galeri Foto
    <textarea data-field="gallery_urls" name="original_ips[{{ $index }}][gallery_urls]" rows="4" maxlength="65000">{{ $item['gallery_urls'] ?? '' }}</textarea>
  </label>
  <small>Satu URL foto per baris. Ubah urutan baris untuk mengurutkan foto; hapus baris untuk menghapus foto dari halaman.</small>
</div>
<div class="field2 full">
  <label>Tambah Foto dari Perangkat
    <input type="file" data-field="photos" data-multiple name="original_ips[{{ $index }}][photos][]" accept="image/jpeg,image/png,image/webp" multiple>
  </label>
  <small>Pilih maksimal 20 foto sekaligus, masing-masing maksimal 5 MB. Foto baru ditambahkan ke galeri setelah disimpan.</small>
</div>
<div class="field2 full">
  <label>Video YouTube
    <textarea data-field="video_urls" name="original_ips[{{ $index }}][video_urls]" rows="4" maxlength="45000">{{ $item['video_urls'] ?? '' }}</textarea>
  </label>
  <small>Satu tautan video YouTube per baris, maksimal 20 video. Video dapat diputar langsung di halaman detail.</small>
</div>
@if (!empty($item['slug']))
  <div class="field2 full"><a class="btn-outline" href="{{ route('original-ip.show', $item['slug']) }}" target="_blank" rel="noopener noreferrer">Lihat Halaman Detail</a></div>
@endif
