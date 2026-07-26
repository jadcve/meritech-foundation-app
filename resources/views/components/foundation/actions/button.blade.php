@props(['variant' => 'primary'])

@php
    $classes = [
        'primary' => 'border-transparent bg-[var(--foundation-color-primary)] text-white hover:opacity-90 focus:ring-[var(--foundation-color-primary)]',
        'secondary' => 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50 focus:ring-slate-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800',
        'danger' => 'border-transparent bg-rose-600 text-white hover:bg-rose-700 focus:ring-rose-500',
        'ghost' => 'border-transparent text-slate-700 hover:bg-slate-100 focus:ring-slate-400 dark:text-slate-200 dark:hover:bg-slate-800',
    ][$variant] ?? 'border-transparent bg-[var(--foundation-color-primary)] text-white hover:opacity-90 focus:ring-[var(--foundation-color-primary)]';
@endphp

<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 rounded-md border px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-950 '.$classes]) }}>
    {{ $slot }}
</button>
