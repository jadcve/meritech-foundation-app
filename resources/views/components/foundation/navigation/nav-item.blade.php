@props(['href' => '#', 'active' => false])

<a
    href="{{ $href }}"
    @class([
        'flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-950',
        'bg-[var(--foundation-color-primary)] text-white focus:ring-[var(--foundation-color-primary)]' => $active,
        'text-slate-700 hover:bg-slate-100 hover:text-slate-950 focus:ring-slate-400 dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-white' => ! $active,
    ])
    @if ($active) aria-current="page" @endif
>
    {{ $slot }}
</a>
