@props(['variant' => 'primary'])

@php
    $classes = [
        'primary' => 'border-transparent bg-[var(--foundation-color-primary)] text-white shadow-lg shadow-slate-900/10 hover:brightness-110 focus:ring-[var(--foundation-color-primary)]',
        'secondary' => 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50 focus:ring-slate-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800',
        'danger' => 'border-transparent bg-rose-600 text-white hover:bg-rose-700 focus:ring-rose-500',
        'ghost' => 'border-transparent text-slate-700 hover:bg-white/80 focus:ring-slate-400 dark:text-slate-200 dark:hover:bg-slate-800',
    ][$variant] ?? 'border-transparent bg-[var(--foundation-color-primary)] text-white shadow-lg shadow-slate-900/10 hover:brightness-110 focus:ring-[var(--foundation-color-primary)]';
@endphp

<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex min-h-11 items-center justify-center gap-2 rounded-2xl border px-4 py-2.5 text-sm font-semibold transition duration-200 focus:outline-none focus:ring-4 focus:ring-offset-2 dark:focus:ring-offset-slate-950 '.$classes]) }}>
    {{ $slot }}
</button>
