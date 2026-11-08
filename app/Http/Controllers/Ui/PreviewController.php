<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Ui\Showcase\PreviewCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function dashboard(Request $request, string $vertical): View
    {
        return view('ui.preview.dashboard', $this->context($request, $vertical));
    }

    public function agenda(Request $request, string $vertical): View
    {
        return view('ui.preview.agenda', $this->context($request, $vertical));
    }

    public function people(Request $request, string $vertical): View
    {
        $context = $this->context($request, $vertical);
        $query = trim((string) $request->query('q', ''));
        $context['query'] = $query;
        $context['people'] = array_values(array_filter(
            $context['people'],
            function (array $person) use ($query): bool {
                if ($query === '') {
                    return true;
                }

                $haystack = mb_strtolower(implode(' ', [
                    $person['name'],
                    $person['city'],
                    $person['email'],
                    $person['meta'] ?? '',
                ]));

                return str_contains($haystack, mb_strtolower($query));
            },
        ));

        return view('ui.preview.people', $context);
    }

    public function person(Request $request, string $vertical, string $person): View
    {
        $context = $this->context($request, $vertical);
        $record = PreviewCatalog::person($vertical, $person);
        abort_if($record === null, 404);
        $context['person'] = $record;
        $context['personEvents'] = array_values(array_filter(
            PreviewCatalog::events($vertical),
            fn (array $event): bool => $event['person_key'] === $person,
        ));

        return view('ui.preview.person', $context);
    }

    /**
     * @return array<string, mixed>
     */
    private function context(Request $request, string $vertical): array
    {
        abort_unless(config('foundation.ui.showcase') === true, 404);
        abort_unless(PreviewCatalog::allows($vertical), 404);

        $theme = match ($request->query('tema')) {
            'oscuro' => 'dark',
            'claro' => 'light',
            default => 'light',
        };

        return [
            'vertical' => $vertical,
            'verticals' => PreviewCatalog::verticals(),
            'vocabulary' => PreviewCatalog::vocabulary($vertical),
            'brand' => PreviewCatalog::brand($vertical),
            'theme' => $theme,
            'kpis' => PreviewCatalog::kpis($vertical),
            'today' => PreviewCatalog::today($vertical),
            'events' => PreviewCatalog::events($vertical),
            'eventsByDay' => collect(PreviewCatalog::days())
                ->map(fn (array $day, int $index): array => array_values(array_filter(
                    PreviewCatalog::events($vertical),
                    fn (array $event): bool => $event['day'] === $index,
                )))
                ->all(),
            'days' => PreviewCatalog::days(),
            'income' => PreviewCatalog::income($vertical),
            'people' => PreviewCatalog::people($vertical),
        ];
    }
}
