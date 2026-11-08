<x-ui.preview-frame
    :vertical="$vertical"
    :verticals="$verticals"
    :vocabulary="$vocabulary"
    :brand="$brand"
    :theme="$theme"
    active="panel"
>
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-500">Semana de muestra · 2 al 8 nov 2026</p>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white">Hoy en {{ $brand['place'] }}</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">Lunes 2 de noviembre. Los números y las personas son de demostración para revisar el sistema visual.</p>
            </div>
            <x-foundation.actions.button type="button">Nueva {{ strtolower($vocabulary['appointment']) }}</x-foundation.actions.button>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kpis as $kpi)
                <x-foundation.stat-card :label="$kpi['label']" :value="$kpi['value']" :trend="$kpi['trend']" />
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
            <x-foundation.card class="!p-0">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                    <h2 class="text-lg font-extrabold tracking-tight">{{ $vocabulary['agenda'] }} de hoy</h2>
                    <a href="{{ route('ui.preview.agenda', ['vertical' => $vertical, 'tema' => $theme === 'dark' ? 'oscuro' : 'claro']) }}" class="text-sm font-semibold" style="color: var(--foundation-color-primary)">Ver semana</a>
                </div>
                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($today as $event)
                        <li class="flex items-start gap-3 px-5 py-4">
                            <div class="w-16 shrink-0 text-sm font-bold text-slate-950 dark:text-white">{{ $event['start'] }}</div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('ui.preview.person', ['vertical' => $vertical, 'person' => $event['person_key'], 'tema' => $theme === 'dark' ? 'oscuro' : 'claro']) }}" class="font-semibold text-slate-950 hover:underline dark:text-white">{{ $event['person'] }}</a>
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $event['title'] }} · {{ $event['place'] }}</p>
                            </div>
                            <x-foundation.data.status-pill :status="$event['status']">{{ $event['end'] }}</x-foundation.data.status-pill>
                        </li>
                    @empty
                        <li class="p-5">
                            <x-foundation.data.empty-state title="Sin actividad hoy" description="Este día de muestra no tiene registros." />
                        </li>
                    @endforelse
                </ul>
            </x-foundation.card>

            <x-foundation.card>
                <h2 class="text-lg font-extrabold tracking-tight">{{ $vocabulary['income'] }}</h2>
                <p class="mt-1 text-sm text-slate-500">Montos de muestra en {{ $brand['currency'] }}.</p>
                @php($max = max(array_column($income, 'amount')) ?: 1)
                <div class="mt-6 flex h-48 items-end gap-2">
                    @foreach ($income as $bar)
                        <div class="flex flex-1 flex-col items-center gap-2">
                            <div class="flex h-36 w-full items-end">
                                <div
                                    class="w-full rounded-t-2xl"
                                    style="height: {{ max(6, (int) round(($bar['amount'] / $max) * 100)) }}%; background: linear-gradient(180deg, var(--foundation-color-accent-secondary), var(--foundation-color-accent));"
                                ></div>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-500">{{ $bar['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </x-foundation.card>
        </div>

        <x-foundation.feedback.alert title="Recordatorio de muestra" variant="info">
            El canal de correo está dibujado como interfaz. WhatsApp y los cobros reales no están conectados.
        </x-foundation.feedback.alert>
    </div>
</x-ui.preview-frame>
