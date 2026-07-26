@props(['label' => 'Actions'])

<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <x-foundation.actions.button variant="secondary" aria-haspopup="menu" x-bind:aria-expanded="open.toString()">{{ $label }}</x-foundation.actions.button>
    </x-slot>
    <x-slot name="content">{{ $slot }}</x-slot>
</x-dropdown>
