@props(['href' => '#', 'active' => false])

<a
    href="{{ $href }}"
    @class([
        'flex min-h-11 items-center gap-2 rounded-2xl px-3 py-2.5 text-sm font-semibold transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-950',
        'bg-[var(--foundation-color-accent)] text-white shadow-md shadow-slate-900/10 focus:ring-[var(--foundation-color-accent)]' => $active,
        'text-slate-700 hover:bg-white hover:text-slate-950 focus:ring-slate-400 dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-white' => ! $active,
    ])
    @if ($active) aria-current="page" @endif
>
    {{ $slot }}
</a>
