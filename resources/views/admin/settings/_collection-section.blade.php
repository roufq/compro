@php
  /**
   * @var string $section
   * @var array{title: string, fields: array<string, string>} $definition
   */
  $rows = old($section, $section === 'original_ips' ? $settings->originalIpItems() : $settings->{$section}) ?? [];
@endphp
<section class="card" data-content-section="{{ $section }}">
  <div class="card-head"><div><h3>{{ $definition['title'] }}</h3><p>Urutan item di sini menentukan urutan pada website.</p></div><button type="button" class="btn-outline" data-add-row>Tambah Item</button></div>
  <div class="card-body">
    <input type="hidden" name="{{ $section }}" value="">
    <div data-rows>
      @foreach ($rows as $index => $item)
        <div class="form-grid content-row" style="border-bottom:1px solid var(--line);padding:18px 0;">
          @foreach ($definition['fields'] as $field => $label)
            @if ($field === 'image_url')
              <div class="field2">
                <label for="{{ $section }}-{{ $index }}-photo">{{ $label }}</label>
                @if (! empty($item['image_url']))
                  <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                    <img src="{{ $item['image_url'] }}" alt="" style="width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid var(--line);">
                    <span style="font-size:11.5px;color:var(--text-faint);">Foto saat ini</span>
                  </div>
                @endif
                <input type="hidden" data-field="image_url" name="{{ $section }}[{{ $index }}][image_url]" value="{{ $item['image_url'] ?? '' }}">
                <input id="{{ $section }}-{{ $index }}-photo" data-field="photo" name="{{ $section }}[{{ $index }}][photo]" type="file" accept="image/jpeg,image/png,image/webp">
                <small>JPG, PNG, atau WebP, maksimal 2 MB.</small>
              </div>
            @else
              <div class="field2">
                <label for="{{ $section }}-{{ $index }}-{{ $field }}">{{ $label }}</label>
                <input id="{{ $section }}-{{ $index }}-{{ $field }}" data-field="{{ $field }}" name="{{ $section }}[{{ $index }}][{{ $field }}]" type="{{ str_contains($field, 'url') ? 'url' : 'text' }}" value="{{ $item[$field] ?? '' }}" @required(in_array($field, ['name', 'role']) || ($section === 'products' && $field === 'url'))>
              </div>
            @endif
          @endforeach
          @if ($section === 'original_ips')
            <x-original-ip-fields :item="$item" :index="$index" />
          @endif
          <div class="field2 full" style="display:flex;gap:10px;flex-direction:row;">
            <button type="button" class="btn-outline" data-move-up>Naik</button><button type="button" class="btn-outline" data-move-down>Turun</button><button type="button" class="btn-outline" data-remove-row>Hapus Item</button>
          </div>
        </div>
      @endforeach
    </div>
    <template>
      <div class="form-grid content-row" style="border-bottom:1px solid var(--line);padding:18px 0;">
        @foreach ($definition['fields'] as $field => $label)
          @if ($field === 'image_url')
            <div class="field2">
              <label>{{ $label }}</label>
              <input type="hidden" data-field="image_url" value="">
              <input data-field="photo" type="file" accept="image/jpeg,image/png,image/webp">
              <small>JPG, PNG, atau WebP, maksimal 2 MB.</small>
            </div>
          @else
            <div class="field2"><label>{{ $label }}<input data-field="{{ $field }}" type="{{ str_contains($field, 'url') ? 'url' : 'text' }}" @required(in_array($field, ['name', 'role']) || ($section === 'products' && $field === 'url'))></label></div>
          @endif
        @endforeach
        @if ($section === 'original_ips')
          <x-original-ip-fields />
        @endif
        <div class="field2 full" style="display:flex;gap:10px;flex-direction:row;">
          <button type="button" class="btn-outline" data-move-up>Naik</button><button type="button" class="btn-outline" data-move-down>Turun</button><button type="button" class="btn-outline" data-remove-row>Hapus Item</button>
        </div>
      </div>
    </template>
  </div>
</section>

@push('scripts')
<script>
document.querySelectorAll('[data-content-section]').forEach(section => {
  if (section.dataset.wired) return;
  section.dataset.wired = '1';
  const rows = section.querySelector('[data-rows]');
  function renumber() {
    [...rows.children].forEach((row, index) => row.querySelectorAll('[data-field]').forEach(input => {
      input.name = `${section.dataset.contentSection}[${index}][${input.dataset.field}]${input.hasAttribute('data-multiple') ? '[]' : ''}`;
    }));
  }
  section.addEventListener('click', event => {
    if (event.target.closest('[data-add-row]')) {
      if (rows.children.length >= 100) return;
      rows.append(section.querySelector('template').content.cloneNode(true));
      rows.lastElementChild.querySelector('input:not([type="hidden"])').focus();
    }
    const row = event.target.closest('.content-row');
    if (row && event.target.closest('[data-remove-row]')) row.remove();
    if (row && event.target.closest('[data-move-up]') && row.previousElementSibling) rows.insertBefore(row, row.previousElementSibling);
    if (row && event.target.closest('[data-move-down]') && row.nextElementSibling) rows.insertBefore(row.nextElementSibling, row);
    renumber();
  });
});
</script>
@endpush
