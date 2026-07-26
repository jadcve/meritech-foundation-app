<footer {{ $attributes->merge(['class' => 'border-t border-slate-200 bg-white px-4 py-4 text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 sm:px-6 lg:px-8']) }}>
    {{ $slot->isEmpty() ? config('foundation.name', 'Meritech Foundation') : $slot }}
</footer>
