@props(['name' => 'search', 'placeholder' => 'Search'])

<label class="block min-w-0 flex-1">
    <span class="sr-only">{{ $placeholder }}</span>
    <input
        name="{{ $name }}"
        type="search"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-[var(--foundation-color-primary)] focus:ring-[var(--foundation-color-primary)] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100']) }}
    >
</label>
