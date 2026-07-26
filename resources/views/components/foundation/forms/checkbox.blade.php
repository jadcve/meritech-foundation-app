@props(['label' => null, 'name' => null])

<label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
    <input
        @if($name) name="{{ $name }}" @endif
        type="checkbox"
        {{ $attributes->merge(['class' => 'rounded border-slate-300 text-[var(--foundation-color-primary)] shadow-sm focus:ring-[var(--foundation-color-primary)] dark:border-slate-700 dark:bg-slate-950']) }}
    >
    @if ($label)
        <span>{{ $label }}</span>
    @else
        {{ $slot }}
    @endif
</label>
