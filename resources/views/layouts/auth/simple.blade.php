@props([
    'siteSettings' => \App\Models\SiteSetting::current(),
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['fluxAppearance' => false])
    </head>
    <body class="min-h-screen bg-[#f5f5f7] text-[#1d1d1f] antialiased">
        <div class="relative isolate min-h-svh overflow-hidden" data-test="auth-brand-shell">
            <div class="pointer-events-none absolute -left-32 -top-32 size-96 rounded-full bg-[#6c5ce7]/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 right-0 size-[28rem] rounded-full bg-[#b8791f]/10 blur-3xl"></div>

            <main class="grid min-h-svh items-center gap-10 px-6 py-10 lg:grid-cols-[1fr_480px] lg:px-14 xl:px-20">
                <section class="hidden h-full min-h-[640px] flex-col justify-between overflow-hidden rounded-[28px] border border-black/10 bg-white p-10 shadow-[0_2px_5px_rgba(0,0,0,.04),0_18px_42px_rgba(0,0,0,.08)] lg:flex">
                    <a href="{{ route('home') }}" class="flex items-center gap-3" wire:navigate data-test="auth-site-link">
                        <span class="flex size-12 items-center justify-center overflow-hidden rounded-2xl border border-black/10 bg-white shadow-sm">
                            <x-app-logo-icon class="size-10 text-[#1d1d1f]" />
                        </span>
                        <span>
                            <span class="block text-sm font-bold uppercase tracking-normal text-[#1d1d1f]">{{ $siteSettings->company_name }}</span>
                            <span class="block text-[10px] font-semibold uppercase tracking-[.14em] text-[#b8791f]">{{ $siteSettings->tagline ?: __('Admin Panel') }}</span>
                        </span>
                    </a>

                    <div class="max-w-xl">
                        <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-4 py-2 text-[11px] font-semibold uppercase tracking-[.14em] text-[#b8791f] shadow-sm">
                            <span class="size-1.5 rounded-full bg-[#b8791f]"></span>
                            {{ $siteSettings->hero_badge_text ?: __('Studio Kreatif Bertenaga AI') }}
                        </span>
                        <h1 class="max-w-2xl text-5xl font-bold leading-tight tracking-normal text-[#1d1d1f]">
                            {{ $siteSettings->hero_title ?: __('Kreativitas tanpa batas, diperkuat kecerdasan buatan') }}
                        </h1>
                        <p class="mt-5 max-w-lg text-base leading-7 text-[#6e6e73]">
                            {{ $siteSettings->hero_description ?: __('Masuk untuk mengelola konten, portofolio, layanan, dan testimoni yang tampil di website utama.') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-3" aria-hidden="true">
                        @if ($siteSettings->hero_tile_1_url)
                            <img src="{{ $siteSettings->hero_tile_1_url }}" alt="" class="h-32 w-full rounded-2xl object-cover">
                        @else
                            <div class="h-32 rounded-2xl bg-linear-to-br from-[#6c5ce7] via-[#8f7bf8] to-[#b8791f]"></div>
                        @endif
                        @if ($siteSettings->hero_tile_2_url)
                            <img src="{{ $siteSettings->hero_tile_2_url }}" alt="" class="h-32 w-full rounded-2xl object-cover">
                        @else
                            <div class="h-32 rounded-2xl bg-linear-to-br from-[#0f2e4c] via-[#2f6fb0] to-[#7b6ef6]"></div>
                        @endif
                        @if ($siteSettings->hero_tile_3_url)
                            <img src="{{ $siteSettings->hero_tile_3_url }}" alt="" class="h-32 w-full rounded-2xl object-cover">
                        @else
                            <div class="h-32 rounded-2xl bg-linear-to-br from-[#332107] via-[#b8791f] to-[#fff2c9]"></div>
                        @endif
                    </div>
                </section>

                <section class="mx-auto flex w-full max-w-md flex-col gap-6">
                    <a href="{{ route('home') }}" class="flex items-center justify-center gap-3 lg:hidden" wire:navigate>
                        <span class="flex size-11 items-center justify-center overflow-hidden rounded-2xl border border-black/10 bg-white shadow-sm">
                            <x-app-logo-icon class="size-9 text-[#1d1d1f]" />
                        </span>
                        <span>
                            <span class="block text-sm font-bold uppercase tracking-normal">{{ $siteSettings->company_name }}</span>
                            <span class="block text-[10px] font-semibold uppercase tracking-[.14em] text-[#b8791f]">{{ $siteSettings->tagline ?: __('Admin Panel') }}</span>
                        </span>
                    </a>

                    <div class="rounded-2xl border border-black/10 bg-white p-7 shadow-[0_2px_5px_rgba(0,0,0,.04),0_14px_34px_rgba(0,0,0,.06)] sm:p-9" data-test="auth-form-card">
                        {{ $slot }}
                    </div>

                    <p class="text-center text-xs text-[#8a8d93]">
                        {{ __('Kembali ke') }}
                        <a href="{{ route('home') }}" class="font-semibold text-[#4b3fe0]" wire:navigate>{{ $siteSettings->company_name }}</a>
                    </p>
                </section>
            </main>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
