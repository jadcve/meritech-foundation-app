@props(['variant' => 'neutral'])

@php
    $classes = [
        'neutral' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
        'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-200',
        'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-200',
        'danger' => 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-200',
        'brand' => 'bg-[var(--foundation-color-primary)] text-white',
    ][$variant] ?? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium '.$classes]) }}>
    {{ $slot }}
</span>
