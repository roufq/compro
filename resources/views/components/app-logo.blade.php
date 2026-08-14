@props([
    'sidebar' => false,
])

@if ($sidebar)
    @if ($siteSettings->logo_url)
        <flux:sidebar.brand
            :name="$siteSettings->company_name"
            :logo="$siteSettings->logo_url"
            :alt="$siteSettings->company_name"
            data-test="dynamic-app-brand"
            {{ $attributes }}
        />
    @else
        <flux:sidebar.brand :name="$siteSettings->company_name" data-test="dynamic-app-brand" {{ $attributes }}>
            <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            </x-slot>
        </flux:sidebar.brand>
    @endif
@else
    @if ($siteSettings->logo_url)
        <flux:brand
            :name="$siteSettings->company_name"
            :logo="$siteSettings->logo_url"
            :alt="$siteSettings->company_name"
            data-test="dynamic-app-brand"
            {{ $attributes }}
        />
    @else
        <flux:brand :name="$siteSettings->company_name" data-test="dynamic-app-brand" {{ $attributes }}>
            <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            </x-slot>
        </flux:brand>
    @endif
@endif
