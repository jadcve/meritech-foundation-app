@props(['show' => false, 'label' => 'Loading'])

<div
    x-data="{ show: @js((bool) $show) }"
    x-show="show"
    class="absolute inset-0 z-40 grid place-items-center bg-white/80 backdrop-blur-sm dark:bg-slate-950/80"
    style="display: {{ $show ? 'grid' : 'none' }};"
    role="status"
    aria-live="polite"
    {{ $attributes }}
>
    <span class="rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white dark:bg-white dark:text-slate-950">{{ $label }}</span>
</div>
