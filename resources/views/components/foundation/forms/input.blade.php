@props(['label' => null, 'name' => null, 'type' => 'text'])

@php($id = $attributes->get('id', $name))

<label class="block">
    @if ($label)
        <span class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $label }}</span>
    @endif
    <input
        @if($name) name="{{ $name }}" @endif
        @if($id) id="{{ $id }}" @endif
        type="{{ $type }}"
        {{ $attributes->except('id')->merge(['class' => 'block min-h-11 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition duration-200 placeholder:text-slate-400 focus:border-[var(--foundation-color-primary)] focus:bg-white focus:ring-4 focus:ring-[color-mix(in_srgb,var(--foundation-color-primary)_20%,transparent)] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:bg-slate-900']) }}
    >
</label>
