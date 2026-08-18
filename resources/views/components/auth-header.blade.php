@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <flux:heading size="xl" class="text-[#1d1d1f]">{{ $title }}</flux:heading>
    <flux:subheading class="text-[#6e6e73]">{{ $description }}</flux:subheading>
</div>
