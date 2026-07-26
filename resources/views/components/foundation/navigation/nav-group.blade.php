@props(['label'])

<section {{ $attributes->merge(['class' => 'space-y-2']) }}>
    <h2 class="px-2 text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">{{ $label }}</h2>
    <div class="space-y-1">
        {{ $slot }}
    </div>
</section>
