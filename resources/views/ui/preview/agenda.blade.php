@php
    $tema = $theme === 'dark' ? 'oscuro' : 'claro';
    $origin = 8 * 60;
@endphp

<x-ui.preview-frame
    :vertical="$vertical"
    :verticals="$verticals"
    :vocabulary="$vocabulary"
    :brand="$brand"
    :theme="$theme"
    active="agenda"
>
    <div class="space-y-5">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-500">2 al 8 de noviembre de 2026</p>
            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $vocabulary['agenda'] }}</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Semana de muestra. En el teléfono los días se apilan; en escritorio se ve la grilla.</p>
        </div>

        <div class="space-y-4 lg:hidden">
            @foreach ($days as $index => $day)
                <x-foundation.card>
                    <h2 class="text-base font-extrabold">{{ $day['label'] }}</h2>
                    @if ($eventsByDay[$index] === [])
                        <div class="mt-3">
                            <x-foundation.data.empty-state title="Día libre" description="No hay registros de muestra en este día." />
                        </div>
                    @else
                        <ul class="mt-3 space-y-3">
                            @foreach ($eventsByDay[$index] as $event)
                                <li class="rounded-2xl border border-slate-100 p-3 dark:border-slate-800" style="background: color-mix(in srgb, var(--foundation-color-accent) 12%, white)">
                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ $event['start'] }}–{{ $event['end'] }} · {{ $event['place'] }}</p>
                                    <a href="{{ route('ui.preview.person', ['vertical' => $vertical, 'person' => $event['person_key'], 'tema' => $tema]) }}" class="mt-1 block font-semibold">{{ $event['person'] }}</a>
                                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $event['title'] }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-foundation.card>
            @endforeach
        </div>

        <x-foundation.card class="hidden overflow-hidden !p-0 lg:block">
            <div class="grid grid-cols-[4.5rem_repeat(7,minmax(0,1fr))] border-b border-slate-100 dark:border-slate-800">
                <div></div>
                @foreach ($days as $day)
                    <div class="border-l border-slate-100 px-2 py-3 text-center dark:border-slate-800">
                        <p class="text-sm font-extrabold">{{ $day['label'] }}</p>
                        <p class="text-[11px] text-slate-500">{{ $day['date'] }}</p>
                    </div>
                @endforeach
            </div>
            <div class="relative">
                @for ($hour = 8; $hour < 20; $hour++)
                    <div class="grid grid-cols-[4.5rem_repeat(7,minmax(0,1fr))]">
                        <div class="h-16 pr-2 text-right text-[11px] font-semibold text-slate-400">{{ sprintf('%02d:00', $hour) }}</div>
                        @foreach ($days as $day)
                            <div class="h-16 border-l border-t border-slate-100 dark:border-slate-800"></div>
                        @endforeach
                    </div>
                @endfor
                @foreach ($events as $event)
                    @php
                        $start = ((int) substr($event['start'], 0, 2) * 60) + (int) substr($event['start'], 3, 2);
                        $end = ((int) substr($event['end'], 0, 2) * 60) + (int) substr($event['end'], 3, 2);
                        $top = (($start - $origin) / 60) * 4;
                        $height = max(2.1, (($end - $start) / 60) * 4);
                    @endphp
                    <a
                        href="{{ route('ui.preview.person', ['vertical' => $vertical, 'person' => $event['person_key'], 'tema' => $tema]) }}"
                        class="absolute overflow-hidden rounded-2xl px-2 py-1 text-white shadow-md"
                        style="left: calc(4.5rem + ((100% - 4.5rem) / 7) * {{ $event['day'] }} + 0.25rem); width: calc(((100% - 4.5rem) / 7) - 0.5rem); top: {{ $top }}rem; height: {{ $height }}rem; background: linear-gradient(160deg, var(--foundation-color-accent), var(--foundation-color-primary));"
                    >
                        <span class="block text-[11px] font-bold">{{ $event['start'] }} {{ $event['person'] }}</span>
                        <span class="block truncate text-[11px] text-white/85">{{ $event['title'] }}</span>
                    </a>
                @endforeach
            </div>
        </x-foundation.card>
    </div>
</x-ui.preview-frame>
