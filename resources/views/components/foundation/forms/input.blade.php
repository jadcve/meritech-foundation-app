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
        {{ $attributes->except('id')->merge(['class' => 'block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-[var(--foundation-color-primary)] focus:ring-[var(--foundation-color-primary)] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100']) }}
    >
</label>
