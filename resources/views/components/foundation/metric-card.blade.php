@props(['label', 'value', 'meta' => null])

<x-foundation.card {{ $attributes }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $value }}</p>
        </div>
        @if ($meta)
            <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $meta }}</span>
        @endif
    </div>
</x-foundation.card>
