@props(['title', 'description' => null, 'href' => null])

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'block rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300 hover:shadow dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700']) }}>
        <h3 class="text-base font-semibold text-slate-950 dark:text-white">{{ $title }}</h3>
        @if ($description)
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $description }}</p>
        @endif
        {{ $slot }}
    </a>
@else
    <div {{ $attributes->merge(['class' => 'rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
        <h3 class="text-base font-semibold text-slate-950 dark:text-white">{{ $title }}</h3>
        @if ($description)
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $description }}</p>
        @endif
        {{ $slot }}
    </div>
@endif
