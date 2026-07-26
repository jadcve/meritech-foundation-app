@props(['label' => null, 'name' => null, 'checked' => false])

<label class="inline-flex items-center gap-3 text-sm text-slate-700 dark:text-slate-200" x-data="{ on: @js((bool) $checked) }">
    <input @if($name) name="{{ $name }}" @endif type="checkbox" x-model="on" {{ $attributes->merge(['class' => 'sr-only']) }}>
    <span class="inline-flex h-6 w-11 items-center rounded-full bg-slate-300 p-1 transition dark:bg-slate-700" :class="{ 'bg-[var(--foundation-color-primary)] dark:bg-[var(--foundation-color-primary)]': on }" aria-hidden="true">
        <span class="h-4 w-4 rounded-full bg-white transition" :class="{ 'translate-x-5': on }"></span>
    </span>
    @if ($label)
        <span>{{ $label }}</span>
    @else
        {{ $slot }}
    @endif
</label>
