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
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-500">{{ $brand['place'] }}</p>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $vocabulary['people'] }}</h1>
            </div>
            <form method="GET" action="{{ route('ui.preview.people', ['vertical' => $vertical]) }}" class="w-full sm:max-w-xs">
                <input type="hidden" name="tema" value="{{ $tema }}">
                <x-foundation.forms.input name="q" label="Buscar" :value="$query" placeholder="Nombre, ciudad o correo" />
            </form>
        </div>

        @if ($people === [])
            <x-foundation.data.empty-state title="Sin coincidencias" description="Prueba con otro nombre de la lista de muestra." />
        @else
            <div class="space-y-3 lg:hidden">
                @foreach ($people as $person)
                    <a href="{{ route('ui.preview.person', ['vertical' => $vertical, 'person' => $person['key'], 'tema' => $tema]) }}" class="block rounded-3xl border border-white/80 bg-white/90 p-4 shadow-foundation dark:border-slate-800 dark:bg-slate-900/90">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-extrabold tracking-tight">{{ $person['name'] }}</p>
                                <p class="text-sm text-slate-500">{{ $person['city'] }} · {{ $person['meta'] }}</p>
                            </div>
                            <x-foundation.data.status-pill :status="$person['status']">{{ $person['status_label'] }}</x-foundation.data.status-pill>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="hidden lg:block">
                <x-foundation.data.table caption="Listado de muestra">
                    <thead class="bg-slate-50/80 text-left text-xs font-bold uppercase tracking-wide text-slate-500 dark:bg-slate-950/40">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Ciudad</th>
                            <th class="px-4 py-3">Detalle</th>
                            <th class="px-4 py-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($people as $person)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                <td class="px-4 py-3 font-semibold">
                                    <a href="{{ route('ui.preview.person', ['vertical' => $vertical, 'person' => $person['key'], 'tema' => $tema]) }}" class="hover:underline">{{ $person['name'] }}</a>
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $person['city'] }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $person['meta'] }}</td>
                                <td class="px-4 py-3"><x-foundation.data.status-pill :status="$person['status']">{{ $person['status_label'] }}</x-foundation.data.status-pill></td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-foundation.data.table>
            </div>
        @endif
    </div>
</x-ui.preview-frame>
