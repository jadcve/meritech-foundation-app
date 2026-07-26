@props(['title' => null])

<header {{ $attributes->merge(['class' => 'sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95']) }}>
    <div class="flex min-h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="min-w-0">
            @isset($leading)
                {{ $leading }}
            @else
                <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $title ?? config('foundation.name', 'Meritech Foundation') }}</p>
            @endisset
        </div>

        @isset($actions)
            <div class="flex shrink-0 items-center gap-2">
                {{ $actions }}
            </div>
        @endisset
    </div>
</header>
