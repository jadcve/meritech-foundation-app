@props(['message' => null])

<div role="status" {{ $attributes->merge(['class' => 'rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-lg dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200']) }}>
    {{ $message ?? $slot }}
</div>
