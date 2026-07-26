@props(['label' => 'Notifications'])

<x-dropdown align="right" width="80">
    <x-slot name="trigger">
        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-md text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:text-slate-300 dark:hover:bg-slate-800" aria-label="{{ $label }}" aria-haspopup="menu" x-bind:aria-expanded="open.toString()">
            <span aria-hidden="true">&middot;</span>
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="p-3 text-sm text-slate-600 dark:text-slate-300">
            {{ $slot }}
        </div>
    </x-slot>
</x-dropdown>
