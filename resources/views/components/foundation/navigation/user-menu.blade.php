@props(['user' => auth()->user()])

<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <button type="button" class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:text-slate-200 dark:hover:bg-slate-800" aria-haspopup="menu" x-bind:aria-expanded="open.toString()">
            <span class="truncate">{{ $user?->name ?? __('User') }}</span>
        </button>
    </x-slot>

    <x-slot name="content">
        {{ $slot }}
    </x-slot>
</x-dropdown>
