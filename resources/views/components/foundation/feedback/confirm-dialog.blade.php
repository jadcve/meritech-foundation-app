@props(['name', 'title', 'confirmLabel' => 'Confirm', 'cancelLabel' => 'Cancel'])

<x-foundation.feedback.modal :name="$name" :title="$title" {{ $attributes }}>
    <div class="text-sm text-slate-600 dark:text-slate-300">{{ $slot }}</div>
    <div class="mt-6 flex justify-end gap-2">
        <x-foundation.actions.button variant="secondary" x-on:click="$dispatch('close-modal', '{{ $name }}')">{{ $cancelLabel }}</x-foundation.actions.button>
        <x-foundation.actions.button variant="danger">{{ $confirmLabel }}</x-foundation.actions.button>
    </div>
</x-foundation.feedback.modal>
