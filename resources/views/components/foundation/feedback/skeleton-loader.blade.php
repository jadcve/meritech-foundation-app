@props(['lines' => 3])

<div {{ $attributes->merge(['class' => 'animate-pulse space-y-3']) }} aria-hidden="true">
    @for ($index = 0; $index < $lines; $index++)
        <div class="h-4 rounded bg-slate-200 dark:bg-slate-800" @if ($index === $lines - 1) style="width: 70%;" @endif></div>
    @endfor
</div>
