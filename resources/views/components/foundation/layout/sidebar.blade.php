@props(['label' => 'Main navigation'])

<nav {{ $attributes->merge(['class' => 'flex h-full flex-col gap-6 px-4 py-5']) }} aria-label="{{ $label }}">
    {{ $slot }}
</nav>
