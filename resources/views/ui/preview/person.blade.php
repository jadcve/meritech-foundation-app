@php($tema = $theme === 'dark' ? 'oscuro' : 'claro')

<x-ui.preview-frame
    :vertical="$vertical"
    :verticals="$verticals"
    :vocabulary="$vocabulary"
    :brand="$brand"
    :theme="$theme"
    active="people"
>
    <div class="space-y-5">
        <a href="{{ route('ui.preview.people', ['vertical' => $vertical, 'tema' => $tema]) }}" class="inline-flex min-h-11 items-center text-sm font-semibold" style="color: var(--foundation-color-primary)">Volver al listado</a>

        <x-foundation.card>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <span class="foundation-mark flex h-16 w-16 items-center justify-center rounded-3xl text-xl font-extrabold text-white">{{ mb_substr($person['name'], 0, 1) }}</span>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">{{ $vocabulary['person'] }}</p>
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $person['name'] }}</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $person['meta'] }} · {{ $person['city'] }}</p>
                </div>
                <x-foundation.data.status-pill :status="$person['status']">{{ $person['status_label'] }}</x-foundation.data.status-pill>
            </div>
            <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $person['note'] }}</p>
            <div class="mt-4 flex flex-wrap gap-2 text-sm">
                <x-foundation.data.badge>{{ $person['phone'] }}</x-foundation.data.badge>
                <x-foundation.data.badge>{{ $person['email'] }}</x-foundation.data.badge>
            </div>
        </x-foundation.card>

        <div class="grid gap-4 lg:grid-cols-2">
            <x-foundation.card>
                <h2 class="text-lg font-extrabold tracking-tight">Ficha de muestra</h2>
                <dl class="mt-4 space-y-3">
                    @foreach ($person['facts'] as $fact)
                        <div class="rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-950/50">
                            <dt class="text-[11px] font-bold uppercase tracking-wide text-slate-500">{{ $fact['label'] }}</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $fact['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-foundation.card>

            <x-foundation.card>
                <h2 class="text-lg font-extrabold tracking-tight">Próximas en la agenda</h2>
                @if ($personEvents === [])
                    <div class="mt-4">
                        <x-foundation.data.empty-state title="Sin horas de muestra" description="Esta ficha no tiene bloques en la semana ilustrada." />
                    </div>
                @else
                    <ul class="mt-4 space-y-3">
                        @foreach ($personEvents as $event)
                            <li class="flex items-center justify-between gap-3 rounded-2xl border border-slate-100 px-3 py-3 dark:border-slate-800">
                                <div>
                                    <p class="font-semibold">{{ $days[$event['day']]['label'] }} · {{ $event['start'] }}</p>
                                    <p class="text-sm text-slate-500">{{ $event['title'] }} · {{ $event['place'] }}</p>
                                </div>
                                <x-foundation.data.status-pill :status="$event['status']">{{ $event['end'] }}</x-foundation.data.status-pill>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-foundation.card>
        </div>

        <x-foundation.card>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-extrabold tracking-tight">Nota interna</h2>
                    <p class="text-sm text-slate-500">El formulario no guarda nada. Sirve para revisar campos, foco y modal.</p>
                </div>
                <x-foundation.actions.button type="button" x-data x-on:click="$dispatch('open-modal', 'nota-muestra')">Abrir nota</x-foundation.actions.button>
            </div>
        </x-foundation.card>

        <x-foundation.feedback.modal name="nota-muestra" title="Nota de muestra">
            <form class="space-y-4" action="#" method="GET" onsubmit="return false">
                <x-foundation.forms.textarea name="nota" label="Observación" placeholder="Escribe una nota ficticia"></x-foundation.forms.textarea>
                <x-foundation.actions.button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'nota-muestra')">Cerrar</x-foundation.actions.button>
            </form>
        </x-foundation.feedback.modal>
    </div>
</x-ui.preview-frame>
