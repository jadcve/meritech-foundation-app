@props(['label', 'variant' => 'ghost'])

<x-foundation.actions.button :variant="$variant" {{ $attributes->merge(['class' => 'h-10 w-10 p-0']) }} aria-label="{{ $label }}">
    {{ $slot }}
</x-foundation.actions.button>
