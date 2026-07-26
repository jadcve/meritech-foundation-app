@props(['status' => 'neutral'])

<x-foundation.data.badge :variant="$status" {{ $attributes }}>
    {{ $slot }}
</x-foundation.data.badge>
