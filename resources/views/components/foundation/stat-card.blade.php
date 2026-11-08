@props(['label', 'value', 'trend' => null])

<x-foundation.card {{ $attributes }}>
    <div class="mb-4 h-1.5 w-14 rounded-full" style="background: linear-gradient(90deg, var(--foundation-color-accent), var(--foundation-color-primary))"></div>
    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $label }}</p>
    <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $value }}</p>
    @if ($trend)
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $trend }}</p>
    @endif
</x-foundation.card>
