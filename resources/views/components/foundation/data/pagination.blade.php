@props(['paginator' => null])

<nav {{ $attributes->merge(['class' => 'flex items-center justify-between gap-3 text-sm']) }} aria-label="Pagination">
    @if ($paginator)
        {{ $paginator->links() }}
    @else
        {{ $slot }}
    @endif
</nav>
