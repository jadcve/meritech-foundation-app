@props(['name', 'title' => null, 'show' => false])

<x-modal :name="$name" :show="$show" {{ $attributes }}>
    <div class="p-6">
        @if ($title)
            <h2 id="{{ $name }}-title" class="text-lg font-semibold text-slate-950 dark:text-white">{{ $title }}</h2>
        @endif
        <div @class(['mt-4' => $title])>
            {{ $slot }}
        </div>
    </div>
</x-modal>
