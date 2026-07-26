@props(['label' => null, 'name' => null])

@php($id = $attributes->get('id', $name))

<label class="block">
    @if ($label)
        <span class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $label }}</span>
    @endif
    <textarea
        @if($name) name="{{ $name }}" @endif
        @if($id) id="{{ $id }}" @endif
        {{ $attributes->except('id')->merge(['class' => 'block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-[var(--foundation-color-primary)] focus:ring-[var(--foundation-color-primary)] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100']) }}
    >{{ $slot }}</textarea>
</label>
