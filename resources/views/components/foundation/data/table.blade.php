@props(['caption' => null])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
            @if ($caption)
                <caption class="sr-only">{{ $caption }}</caption>
            @endif
            {{ $slot }}
        </table>
    </div>
</div>
