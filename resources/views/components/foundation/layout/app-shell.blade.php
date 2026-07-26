@props([
    'title' => null,
])

@php
    $branding = app(\App\Core\Branding\BrandingManager::class);
    $brandName = $title ?? $branding->name();
@endphp

<div
    {{ $attributes->merge([
        'class' => 'min-h-screen bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100',
    ]) }}
    style="--foundation-color-primary: {{ $branding->primaryColor() }}; --foundation-color-secondary: {{ $branding->secondaryColor() }}; --foundation-color-background: #f8fafc; --foundation-color-surface: #ffffff; --foundation-color-text: #0f172a; --foundation-color-muted: #64748b; --foundation-radius-sm: 0.375rem; --foundation-radius-md: 0.5rem; --foundation-radius-lg: 0.5rem; --foundation-shadow-sm: 0 1px 2px 0 rgb(15 23 42 / 0.05); --foundation-shadow-md: 0 4px 6px -1px rgb(15 23 42 / 0.1); --foundation-primary: var(--foundation-color-primary); --foundation-secondary: var(--foundation-color-secondary);"
>
    <div class="flex min-h-screen">
        @isset($sidebar)
            <aside class="hidden w-72 shrink-0 border-r border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900 lg:block">
                {{ $sidebar }}
            </aside>
        @endisset

        <div class="flex min-w-0 flex-1 flex-col">
            @isset($topbar)
                {{ $topbar }}
            @else
                <x-foundation.layout.topbar :title="$brandName" />
            @endisset

            @isset($header)
                <x-foundation.layout.page-header>
                    {{ $header }}
                </x-foundation.layout.page-header>
            @endisset

            <main class="flex-1">
                {{ $slot }}
            </main>

            @isset($footer)
                {{ $footer }}
            @else
                <x-foundation.layout.footer />
            @endisset
        </div>
    </div>
</div>
