@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-dashed border-slate-300 bg-white px-6 py-10 text-center dark:border-slate-700 dark:bg-slate-900']) }}>
    <h3 class="text-base font-semibold text-slate-950 dark:text-white">{{ $title }}</h3>
    @if ($description)
        <p class="mx-auto mt-1 max-w-md text-sm text-slate-600 dark:text-slate-300">{{ $description }}</p>
    @endif
    @if (! $slot->isEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
