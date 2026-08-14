@props([
    'src' => null,
    'alt' => 'Logo perusahaan',
    'initials' => null,
    'size' => 'md',
])

@if ($src)
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        data-test="company-logo-avatar"
        {{ $attributes->class([
            'size-8' => $size === 'sm',
            'size-10' => $size !== 'sm',
            'shrink-0 rounded-full border border-zinc-200 bg-white object-contain p-0.5 shadow-sm dark:border-zinc-700',
        ]) }}
    >
@else
    <flux:avatar :initials="$initials" :size="$size" circle {{ $attributes }} />
@endif
