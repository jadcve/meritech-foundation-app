@props(['title' => null, 'description' => null])

<header {{ $attributes->merge(['class' => 'border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900']) }}>
    <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-5 sm:px-6 lg:px-8">
        @isset($breadcrumbs)
            {{ $breadcrumbs }}
        @endisset

        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                @if ($title)
                    <h1 class="truncate text-2xl font-semibold text-slate-950 dark:text-white">{{ $title }}</h1>
                @else
                    <div class="text-2xl font-semibold text-slate-950 dark:text-white">{{ $slot }}</div>
                @endif

                @if ($description)
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $description }}</p>
                @endif
            </div>

            @isset($actions)
                <div class="flex shrink-0 flex-wrap items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    </div>
</header>
