@props(['label', 'value', 'trend' => null])

<x-foundation.card {{ $attributes }}>
    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
    <p class="mt-2 text-3xl font-semibold text-slate-950 dark:text-white">{{ $value }}</p>
    @if ($trend)
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $trend }}</p>
    @endif
</x-foundation.card>
