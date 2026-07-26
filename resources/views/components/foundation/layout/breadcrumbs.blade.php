@props(['items' => []])

<nav {{ $attributes->merge(['class' => 'text-sm text-slate-500 dark:text-slate-400']) }} aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2">
        @foreach ($items as $item)
            <li class="flex items-center gap-2">
                @if (! $loop->first)
                    <span aria-hidden="true">/</span>
                @endif

                @if (($item['url'] ?? null) && ! $loop->last)
                    <a class="hover:text-slate-900 dark:hover:text-white" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                @else
                    <span @if ($loop->last) aria-current="page" @endif>{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach

        {{ $slot }}
    </ol>
</nav>
