@props([
    'vertical',
    'verticals',
    'vocabulary',
    'brand',
    'theme' => 'light',
    'active' => 'panel',
])

@php
    $tema = $theme === 'dark' ? 'oscuro' : 'claro';
    $otherTema = $theme === 'dark' ? 'claro' : 'oscuro';
    $link = fn (string $name, array $params = []): string => route($name, array_merge([
        'vertical' => $vertical,
        'tema' => $tema,
    ], $params));
    $preset = $verticals[$vertical] ?? ['mark' => 'UI', 'label' => 'Vista'];
@endphp

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $brand['name'] }} · {{ $preset['label'] }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans">
        <x-foundation.layout.app-shell
            :title="$brand['name']"
            :primary="$brand['primary']"
            :secondary="$brand['secondary']"
            :accent="$preset['accent']"
            :vertical="$vertical"
            :theme="$theme"
        >
            <x-slot name="sidebar">
                <x-foundation.layout.sidebar label="Navegación de la vista previa">
                    <div class="flex items-center gap-3 px-1">
                        <span class="foundation-mark flex h-12 w-12 items-center justify-center rounded-2xl text-sm font-extrabold text-white shadow-lg shadow-slate-900/15">{{ $preset['mark'] }}</span>
                        <span class="min-w-0">
                            <span class="block truncate text-base font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $brand['name'] }}</span>
                            <span class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $brand['place'] }}</span>
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        @foreach ($verticals as $key => $item)
                            <a
                                href="{{ route('ui.preview.dashboard', ['vertical' => $key, 'tema' => $tema]) }}"
                                class="rounded-2xl border px-2 py-2 text-center text-[11px] font-bold uppercase tracking-wide {{ $key === $vertical ? 'border-transparent text-white' : 'border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300' }}"
                                @if ($key === $vertical) style="background: var(--foundation-color-accent)" aria-current="page" @endif
                            >{{ $item['label'] }}</a>
                        @endforeach
                    </div>

                    <div class="flex flex-col gap-1">
                        <x-foundation.navigation.nav-item :href="$link('ui.preview.dashboard')" :active="$active === 'panel'">Panel</x-foundation.navigation.nav-item>
                        <x-foundation.navigation.nav-item :href="$link('ui.preview.agenda')" :active="$active === 'agenda'">{{ $vocabulary['agenda'] }}</x-foundation.navigation.nav-item>
                        <x-foundation.navigation.nav-item :href="$link('ui.preview.people')" :active="$active === 'people'">{{ $vocabulary['people'] }}</x-foundation.navigation.nav-item>
                    </div>

                    <div class="mt-auto rounded-3xl bg-slate-950 p-4 text-white shadow-foundation-lg ring-1 ring-white/10 dark:bg-slate-800">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-white/60">Marca del tenant</p>
                        <p class="mt-2 text-sm font-semibold text-white">El color de acción sigue a la marca. El acento sigue a la vertical.</p>
                        <div class="mt-3 flex gap-2">
                            <span class="h-8 flex-1 rounded-full" style="background: var(--foundation-color-primary)"></span>
                            <span class="h-8 flex-1 rounded-full" style="background: var(--foundation-color-accent)"></span>
                            <span class="h-8 flex-1 rounded-full" style="background: var(--foundation-color-accent-secondary)"></span>
                        </div>
                    </div>
                </x-foundation.layout.sidebar>
            </x-slot>

            <x-slot name="topbar">
                <x-foundation.layout.topbar :title="$brand['name']">
                    <x-slot name="leading">
                        <div class="hidden sm:block">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">Vista previa</p>
                            <p class="truncate text-sm font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $preset['label'] }} · {{ $brand['name'] }}</p>
                        </div>
                    </x-slot>
                    <x-slot name="actions">
                        <x-foundation.data.badge variant="brand">Datos ficticios</x-foundation.data.badge>
                        <a href="{{ request()->fullUrlWithQuery(['tema' => $otherTema]) }}" class="inline-flex min-h-11 items-center rounded-2xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            {{ $theme === 'dark' ? 'Tema claro' : 'Tema oscuro' }}
                        </a>
                    </x-slot>
                </x-foundation.layout.topbar>
            </x-slot>

            <x-slot name="footer">
                <x-foundation.layout.footer>
                    Vista previa de Foundation · sin datos reales de pacientes, órdenes ni pagos.
                </x-foundation.layout.footer>
            </x-slot>

            <div class="mx-auto w-full max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </x-foundation.layout.app-shell>
    </body>
</html>
