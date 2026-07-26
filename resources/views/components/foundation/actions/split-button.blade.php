@props(['label'])

<div {{ $attributes->merge(['class' => 'inline-flex rounded-md shadow-sm']) }}>
    <x-foundation.actions.button class="rounded-r-none">{{ $label }}</x-foundation.actions.button>
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button type="button" class="inline-flex items-center justify-center rounded-l-none rounded-r-md border border-l-white/20 border-transparent bg-[var(--foundation-color-primary)] px-3 py-2 text-sm font-medium text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--foundation-color-primary)] focus:ring-offset-2 dark:focus:ring-offset-slate-950" aria-label="{{ $label }} options" aria-haspopup="menu" x-bind:aria-expanded="open.toString()">
                <span aria-hidden="true">v</span>
            </button>
        </x-slot>
        <x-slot name="content">{{ $slot }}</x-slot>
    </x-dropdown>
</div>
