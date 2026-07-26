<form {{ $attributes->merge(['class' => 'flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-end']) }}>
    {{ $slot }}
</form>
