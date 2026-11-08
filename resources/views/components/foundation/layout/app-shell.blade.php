@props([
    'title' => null,
    'primary' => null,
    'secondary' => null,
    'accent' => null,
    'vertical' => null,
    'theme' => null,
])

@php
    $branding = app(\App\Core\Branding\BrandingManager::class);
    $brandName = $title ?? $branding->name();

    $safeColor = function (?string $color, string $fallback): string {
        $color = trim((string) $color);

        return preg_match('/^#[0-9A-Fa-f]{6}$/', $color) === 1 ? $color : $fallback;
    };

    $verticalKey = is_string($vertical) ? $vertical : null;
    $preset = config('foundation.ui.verticals.'.$verticalKey);
    $primaryColor = $safeColor($primary ?: $branding->primaryColor(), '#2563eb');
    $secondaryColor = $safeColor($secondary ?: $branding->secondaryColor(), '#64748b');
    $accentColor = $safeColor($accent ?: ($preset['accent'] ?? $primaryColor), $primaryColor);
    $accentSecondary = $safeColor($preset['accent_secondary'] ?? $secondaryColor, $secondaryColor);
    $themeMode = in_array($theme ?: $branding->theme(), ['light', 'dark', 'system'], true)
        ? ($theme ?: $branding->theme())
        : 'system';
@endphp

<div
    {{ $attributes->merge([
        'class' => 'foundation-shell min-h-screen antialiased'.($themeMode === 'dark' ? ' dark' : ''),
    ]) }}
    data-foundation-vertical="{{ $verticalKey }}"
    data-foundation-theme="{{ $themeMode }}"
    x-data='{
        navOpen: false,
        themeMode: @json($themeMode),
        dark: @json($themeMode === "dark"),
        syncTheme() {
            this.dark = this.themeMode === "dark" || (this.themeMode !== "light" && window.matchMedia("(prefers-color-scheme: dark)").matches);
        }
    }'
    x-init="syncTheme()"
    :class="{ dark: dark }"
    style="--foundation-color-primary: {{ $primaryColor }}; --foundation-color-secondary: {{ $secondaryColor }}; --foundation-color-accent: {{ $accentColor }}; --foundation-color-accent-secondary: {{ $accentSecondary }}; --foundation-primary: var(--foundation-color-primary); --foundation-secondary: var(--foundation-color-secondary);"
>
    <div class="flex min-h-screen">
        @isset($sidebar)
            <div
                x-cloak
                x-show="navOpen"
                x-transition.opacity
                class="fixed inset-0 z-40 bg-slate-950/55 backdrop-blur-sm lg:hidden"
                @click="navOpen = false"
            ></div>
            <aside
                class="z-30 w-72 shrink-0 border-r border-white/70 bg-white/90 shadow-foundation backdrop-blur dark:border-slate-800 dark:bg-slate-950/95 max-lg:fixed max-lg:inset-y-0 max-lg:left-0 lg:sticky lg:top-0 lg:z-auto lg:h-screen"
                :style="navOpen || window.matchMedia('(min-width: 1024px)').matches ? 'transform: none' : 'transform: translateX(-100%)'"
                @click="if ($event.target.closest('a')) navOpen = false"
            >
                {{ $sidebar }}
            </aside>
        @endisset

        <div class="flex min-w-0 flex-1 flex-col">
            @isset($sidebar)
                <div class="sticky top-0 z-30 flex items-center gap-3 border-b border-white/70 bg-white/80 px-4 py-3 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80 lg:hidden">
                    <button
                        type="button"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl text-white shadow-lg shadow-slate-900/10 foundation-mark"
                        @click="navOpen = true"
                        aria-label="Open navigation"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h10" />
                        </svg>
                    </button>
                    <p class="truncate text-sm font-extrabold tracking-tight">{{ $brandName }}</p>
                </div>
            @endisset

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
