<?php

namespace App\Ui\Showcase;

final class PreviewCatalog
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function verticals(): array
    {
        return config('foundation.ui.verticals', []);
    }

    public static function allows(string $vertical): bool
    {
        return array_key_exists($vertical, self::verticals());
    }

    /**
     * @return array<string, string>
     */
    public static function vocabulary(string $vertical): array
    {
        return match ($vertical) {
            'health' => [
                'person' => 'Paciente',
                'people' => 'Pacientes',
                'appointment' => 'Atención',
                'agenda' => 'Agenda clínica',
                'income' => 'Ingresos del día',
                'place' => 'Sede',
            ],
            'mechanic' => [
                'person' => 'Cliente',
                'people' => 'Clientes',
                'appointment' => 'Orden',
                'agenda' => 'Agenda de taller',
                'income' => 'Caja del día',
                'place' => 'Taller',
            ],
            default => [
                'person' => 'Cliente',
                'people' => 'Clientes',
                'appointment' => 'Cita',
                'agenda' => 'Agenda',
                'income' => 'Ingresos del día',
                'place' => 'Sucursal',
            ],
        };
    }

    /**
     * @return array{name: string, place: string, primary: string, secondary: string, currency: string}
     */
    public static function brand(string $vertical): array
    {
        return match ($vertical) {
            'health' => [
                'name' => 'Andes Salud',
                'place' => 'Sede Providencia',
                'primary' => '#1d4ed8',
                'secondary' => '#0ea5e9',
                'currency' => 'CLP',
            ],
            'mechanic' => [
                'name' => 'Ruta Sur',
                'place' => 'Taller Ñuñoa',
                'primary' => '#9a3412',
                'secondary' => '#65a30d',
                'currency' => 'CLP',
            ],
            default => [
                'name' => 'Lumina Studio',
                'place' => 'Sucursal Lastarria',
                'primary' => '#be185d',
                'secondary' => '#f59e0b',
                'currency' => 'CLP',
            ],
        };
    }

    /**
     * @return array<int, array<string, string>>
     */
    public static function kpis(string $vertical): array
    {
        $vocabulary = self::vocabulary($vertical);

        return match ($vertical) {
            'health' => [
                ['label' => $vocabulary['people'].' hoy', 'value' => '18', 'trend' => '+3 respecto del lunes'],
                ['label' => $vocabulary['income'], 'value' => '$642.000', 'trend' => '12 atenciones cobradas'],
                ['label' => 'Ocupación', 'value' => '81%', 'trend' => 'Box 2 con espacio a las 16:00'],
                ['label' => 'Pendientes', 'value' => '4', 'trend' => 'Resultados de muestra por revisar'],
            ],
            'mechanic' => [
                ['label' => 'Órdenes abiertas', 'value' => '11', 'trend' => '3 entran hoy'],
                ['label' => $vocabulary['income'], 'value' => '$890.500', 'trend' => '2 pagos en efectivo'],
                ['label' => 'Bahías', 'value' => '3/4', 'trend' => 'Bahía 4 libre desde las 15:30'],
                ['label' => 'Repuestos', 'value' => '2', 'trend' => 'Esperando filtro y pastillas'],
            ],
            default => [
                ['label' => 'Citas de hoy', 'value' => '14', 'trend' => '+2 walk-in confirmados'],
                ['label' => $vocabulary['income'], 'value' => '$428.000', 'trend' => 'Ticket promedio $30.570'],
                ['label' => 'Ocupación', 'value' => '76%', 'trend' => 'Silla 3 libre a las 13:00'],
                ['label' => 'Por confirmar', 'value' => '5', 'trend' => 'Recordatorio de mañana'],
            ],
        };
    }

    /**
     * @return array<int, array{day: int, start: string, end: string, title: string, person: string, person_key: string, status: string, place: string}>
     */
    public static function events(string $vertical): array
    {
        return match ($vertical) {
            'health' => [
                ['day' => 0, 'start' => '09:00', 'end' => '09:30', 'title' => 'Control general', 'person' => 'Camila Soto', 'person_key' => 'camila-soto', 'status' => 'success', 'place' => 'Box 1'],
                ['day' => 0, 'start' => '10:00', 'end' => '10:45', 'title' => 'Toma de muestra', 'person' => 'Luis Herrera', 'person_key' => 'luis-herrera', 'status' => 'warning', 'place' => 'Box 2'],
                ['day' => 0, 'start' => '15:30', 'end' => '16:10', 'title' => 'Entrega de resultados', 'person' => 'Ana Pérez', 'person_key' => 'ana-perez', 'status' => 'brand', 'place' => 'Recepción'],
                ['day' => 1, 'start' => '08:30', 'end' => '09:15', 'title' => 'Evaluación', 'person' => 'Mateo Rivas', 'person_key' => 'mateo-rivas', 'status' => 'success', 'place' => 'Box 1'],
                ['day' => 2, 'start' => '11:00', 'end' => '11:40', 'title' => 'Control', 'person' => 'Valentina Díaz', 'person_key' => 'valentina-diaz', 'status' => 'neutral', 'place' => 'Box 3'],
                ['day' => 3, 'start' => '16:00', 'end' => '16:30', 'title' => 'Indicaciones', 'person' => 'Camila Soto', 'person_key' => 'camila-soto', 'status' => 'success', 'place' => 'Box 1'],
                ['day' => 4, 'start' => '09:30', 'end' => '10:20', 'title' => 'Primera visita', 'person' => 'José Navarro', 'person_key' => 'jose-navarro', 'status' => 'warning', 'place' => 'Box 2'],
            ],
            'mechanic' => [
                ['day' => 0, 'start' => '08:30', 'end' => '10:30', 'title' => 'Frenos delanteros', 'person' => 'Pedro Rojas', 'person_key' => 'pedro-rojas', 'status' => 'warning', 'place' => 'Bahía 1'],
                ['day' => 0, 'start' => '11:00', 'end' => '12:30', 'title' => 'Cambio de aceite', 'person' => 'María León', 'person_key' => 'maria-leon', 'status' => 'success', 'place' => 'Bahía 2'],
                ['day' => 1, 'start' => '09:00', 'end' => '13:00', 'title' => 'Diagnóstico eléctrico', 'person' => 'Hugo Salas', 'person_key' => 'hugo-salas', 'status' => 'brand', 'place' => 'Bahía 3'],
                ['day' => 2, 'start' => '15:00', 'end' => '17:00', 'title' => 'Alineación', 'person' => 'Carla Muñoz', 'person_key' => 'carla-munoz', 'status' => 'neutral', 'place' => 'Bahía 4'],
                ['day' => 4, 'start' => '08:00', 'end' => '11:00', 'title' => 'Entrega Yaris', 'person' => 'Pedro Rojas', 'person_key' => 'pedro-rojas', 'status' => 'success', 'place' => 'Recepción'],
            ],
            default => [
                ['day' => 0, 'start' => '09:00', 'end' => '10:30', 'title' => 'Corte y color', 'person' => 'Camila Soto', 'person_key' => 'camila-soto', 'status' => 'success', 'place' => 'Silla 1'],
                ['day' => 0, 'start' => '11:00', 'end' => '11:45', 'title' => 'Barba', 'person' => 'Luis Herrera', 'person_key' => 'luis-herrera', 'status' => 'brand', 'place' => 'Silla 2'],
                ['day' => 0, 'start' => '16:00', 'end' => '17:30', 'title' => 'Mechas', 'person' => 'Ana Pérez', 'person_key' => 'ana-perez', 'status' => 'warning', 'place' => 'Silla 3'],
                ['day' => 1, 'start' => '10:00', 'end' => '11:00', 'title' => 'Brushing', 'person' => 'Valentina Díaz', 'person_key' => 'valentina-diaz', 'status' => 'success', 'place' => 'Silla 1'],
                ['day' => 2, 'start' => '13:00', 'end' => '14:00', 'title' => 'Corte niño', 'person' => 'Mateo Rivas', 'person_key' => 'mateo-rivas', 'status' => 'neutral', 'place' => 'Silla 2'],
                ['day' => 3, 'start' => '18:00', 'end' => '19:15', 'title' => 'Peinado evento', 'person' => 'Camila Soto', 'person_key' => 'camila-soto', 'status' => 'success', 'place' => 'Silla 1'],
                ['day' => 5, 'start' => '12:00', 'end' => '13:30', 'title' => 'Color raíz', 'person' => 'José Navarro', 'person_key' => 'jose-navarro', 'status' => 'warning', 'place' => 'Silla 3'],
            ],
        };
    }

    /**
     * @return array<int, array{label: string, date: string}>
     */
    public static function days(): array
    {
        return [
            ['label' => 'Lun 2', 'date' => '2 nov'],
            ['label' => 'Mar 3', 'date' => '3 nov'],
            ['label' => 'Mié 4', 'date' => '4 nov'],
            ['label' => 'Jue 5', 'date' => '5 nov'],
            ['label' => 'Vie 6', 'date' => '6 nov'],
            ['label' => 'Sáb 7', 'date' => '7 nov'],
            ['label' => 'Dom 8', 'date' => '8 nov'],
        ];
    }

    /**
     * @return array<int, array{label: string, amount: int}>
     */
    public static function income(string $vertical): array
    {
        return match ($vertical) {
            'health' => [
                ['label' => 'Lun', 'amount' => 510000],
                ['label' => 'Mar', 'amount' => 640000],
                ['label' => 'Mié', 'amount' => 480000],
                ['label' => 'Jue', 'amount' => 720000],
                ['label' => 'Vie', 'amount' => 690000],
                ['label' => 'Sáb', 'amount' => 310000],
                ['label' => 'Dom', 'amount' => 0],
            ],
            'mechanic' => [
                ['label' => 'Lun', 'amount' => 890000],
                ['label' => 'Mar', 'amount' => 420000],
                ['label' => 'Mié', 'amount' => 1250000],
                ['label' => 'Jue', 'amount' => 360000],
                ['label' => 'Vie', 'amount' => 980000],
                ['label' => 'Sáb', 'amount' => 540000],
                ['label' => 'Dom', 'amount' => 0],
            ],
            default => [
                ['label' => 'Lun', 'amount' => 428000],
                ['label' => 'Mar', 'amount' => 390000],
                ['label' => 'Mié', 'amount' => 510000],
                ['label' => 'Jue', 'amount' => 460000],
                ['label' => 'Vie', 'amount' => 610000],
                ['label' => 'Sáb', 'amount' => 740000],
                ['label' => 'Dom', 'amount' => 0],
            ],
        };
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function people(string $vertical): array
    {
        $shared = [
            'camila-soto' => ['name' => 'Camila Soto', 'city' => 'Santiago', 'phone' => '+56 9 6123 4401', 'email' => 'camila.soto@example.com', 'status' => 'success', 'status_label' => 'Activa'],
            'luis-herrera' => ['name' => 'Luis Herrera', 'city' => 'Valparaíso', 'phone' => '+56 9 4410 2288', 'email' => 'luis.herrera@example.com', 'status' => 'warning', 'status_label' => 'Por confirmar'],
            'ana-perez' => ['name' => 'Ana Pérez', 'city' => 'Caracas', 'phone' => '+58 412 555 0192', 'email' => 'ana.perez@example.com', 'status' => 'brand', 'status_label' => 'Nueva'],
            'mateo-rivas' => ['name' => 'Mateo Rivas', 'city' => 'Concepción', 'phone' => '+56 9 7781 0033', 'email' => 'mateo.rivas@example.com', 'status' => 'neutral', 'status_label' => 'Inactivo'],
            'valentina-diaz' => ['name' => 'Valentina Díaz', 'city' => 'Viña del Mar', 'phone' => '+56 9 2204 1180', 'email' => 'valentina.diaz@example.com', 'status' => 'success', 'status_label' => 'Activa'],
            'jose-navarro' => ['name' => 'José Navarro', 'city' => 'Maracaibo', 'phone' => '+58 414 200 7741', 'email' => 'jose.navarro@example.com', 'status' => 'warning', 'status_label' => 'Pendiente'],
        ];

        $extra = match ($vertical) {
            'health' => [
                'camila-soto' => ['meta' => 'Control anual', 'note' => 'Prefiere horario de mañana.', 'facts' => [
                    ['label' => 'Documento', 'value' => '12.445.901-3'],
                    ['label' => 'Antecedente de muestra', 'value' => 'Alergia declarada a penicilina'],
                    ['label' => 'Indicación de muestra', 'value' => 'Control en 30 días'],
                    ['label' => 'Resultado de muestra', 'value' => 'Hemograma dentro de rango. Origen ilustrativo, no es una integración.'],
                ]],
                'luis-herrera' => ['meta' => 'Toma de muestra', 'note' => 'Ayuno informado por el paciente.', 'facts' => [
                    ['label' => 'Documento', 'value' => '17.902.114-K'],
                    ['label' => 'Antecedente de muestra', 'value' => 'Sin alergias declaradas'],
                    ['label' => 'Indicación de muestra', 'value' => 'Hidratación y reposo relativo'],
                    ['label' => 'Resultado de muestra', 'value' => 'Pendiente de revisión visual'],
                ]],
                'ana-perez' => ['meta' => 'Primera atención', 'note' => 'Viaja entre Caracas y Santiago.', 'facts' => [
                    ['label' => 'Documento', 'value' => 'V-18.442.110'],
                    ['label' => 'Antecedente de muestra', 'value' => 'No registrado en esta vista'],
                    ['label' => 'Indicación de muestra', 'value' => 'Completar ficha en la próxima visita'],
                    ['label' => 'Resultado de muestra', 'value' => 'Sin resultados cargados'],
                ]],
                'mateo-rivas' => ['meta' => 'Sin controles recientes', 'note' => 'Última visita hace seis meses.', 'facts' => [
                    ['label' => 'Documento', 'value' => '20.118.334-5'],
                    ['label' => 'Antecedente de muestra', 'value' => 'Asma leve'],
                    ['label' => 'Indicación de muestra', 'value' => 'Ninguna vigente'],
                    ['label' => 'Resultado de muestra', 'value' => 'Archivo ilustrativo vacío'],
                ]],
                'valentina-diaz' => ['meta' => 'Seguimiento', 'note' => 'Confirma por WhatsApp.', 'facts' => [
                    ['label' => 'Documento', 'value' => '19.004.882-1'],
                    ['label' => 'Antecedente de muestra', 'value' => 'Ninguno relevante'],
                    ['label' => 'Indicación de muestra', 'value' => 'Revisión en dos semanas'],
                    ['label' => 'Resultado de muestra', 'value' => 'Perfil dentro de valores de referencia'],
                ]],
                'jose-navarro' => ['meta' => 'Evaluación inicial', 'note' => 'Llega con orden externa de muestra.', 'facts' => [
                    ['label' => 'Documento', 'value' => 'V-22.901.004'],
                    ['label' => 'Antecedente de muestra', 'value' => 'Hipertensión en tratamiento'],
                    ['label' => 'Indicación de muestra', 'value' => 'Traer medicación habitual'],
                    ['label' => 'Resultado de muestra', 'value' => 'Aún no disponible'],
                ]],
            ],
            'mechanic' => [
                'pedro-rojas' => ['name' => 'Pedro Rojas', 'city' => 'Santiago', 'phone' => '+56 9 5551 2020', 'email' => 'pedro.rojas@example.com', 'status' => 'warning', 'status_label' => 'En bahía', 'meta' => 'Toyota Yaris 2018', 'note' => 'Pastillas delanteras y rectificado.', 'facts' => [
                    ['label' => 'Patente', 'value' => 'JKDP-42'],
                    ['label' => 'Orden', 'value' => 'OT-1042'],
                    ['label' => 'Promesa', 'value' => 'Viernes 6 nov, 11:00'],
                ]],
                'maria-leon' => ['name' => 'María León', 'city' => 'La Florida', 'phone' => '+56 9 3001 8844', 'email' => 'maria.leon@example.com', 'status' => 'success', 'status_label' => 'Lista', 'meta' => 'Kia Morning 2020', 'note' => 'Aceite y filtro cambiados.', 'facts' => [
                    ['label' => 'Patente', 'value' => 'RTWX-11'],
                    ['label' => 'Orden', 'value' => 'OT-1048'],
                    ['label' => 'Promesa', 'value' => 'Hoy, 13:00'],
                ]],
                'hugo-salas' => ['name' => 'Hugo Salas', 'city' => 'Maipú', 'phone' => '+56 9 6670 1414', 'email' => 'hugo.salas@example.com', 'status' => 'brand', 'status_label' => 'Diagnóstico', 'meta' => 'Chevrolet Sail 2016', 'note' => 'Falla intermitente al encender.', 'facts' => [
                    ['label' => 'Patente', 'value' => 'HGSK-90'],
                    ['label' => 'Orden', 'value' => 'OT-1051'],
                    ['label' => 'Promesa', 'value' => 'Martes 3 nov'],
                ]],
                'carla-munoz' => ['name' => 'Carla Muñoz', 'city' => 'Ñuñoa', 'phone' => '+56 9 2121 9090', 'email' => 'carla.munoz@example.com', 'status' => 'neutral', 'status_label' => 'Agendada', 'meta' => 'Mazda 3 2019', 'note' => 'Alineación después del cambio de neumáticos.', 'facts' => [
                    ['label' => 'Patente', 'value' => 'PLQA-18'],
                    ['label' => 'Orden', 'value' => 'OT-1055'],
                    ['label' => 'Promesa', 'value' => 'Miércoles 4 nov'],
                ]],
            ],
            default => [
                'camila-soto' => ['meta' => 'Color y corte', 'note' => 'Tono habano, sin amoníaco.', 'facts' => [
                    ['label' => 'Preferencia', 'value' => 'Silla 1, mañana'],
                    ['label' => 'Última visita', 'value' => '12 oct 2026'],
                    ['label' => 'Ticket habitual', 'value' => '$46.000'],
                ]],
                'luis-herrera' => ['meta' => 'Barba cada 3 semanas', 'note' => 'Llega directo desde la oficina.', 'facts' => [
                    ['label' => 'Preferencia', 'value' => 'Silla 2'],
                    ['label' => 'Última visita', 'value' => '20 oct 2026'],
                    ['label' => 'Ticket habitual', 'value' => '$16.000'],
                ]],
                'ana-perez' => ['meta' => 'Mechas', 'note' => 'Primera visita en esta sucursal.', 'facts' => [
                    ['label' => 'Preferencia', 'value' => 'Tarde'],
                    ['label' => 'Última visita', 'value' => 'Sin historial'],
                    ['label' => 'Ticket habitual', 'value' => 'Por cotizar'],
                ]],
                'mateo-rivas' => ['meta' => 'Corte infantil', 'note' => 'Viene con un adulto.', 'facts' => [
                    ['label' => 'Preferencia', 'value' => 'Sábado'],
                    ['label' => 'Última visita', 'value' => '2 ago 2026'],
                    ['label' => 'Ticket habitual', 'value' => '$12.000'],
                ]],
                'valentina-diaz' => ['meta' => 'Brushing', 'note' => 'Confirma el mismo día.', 'facts' => [
                    ['label' => 'Preferencia', 'value' => 'Mediodía'],
                    ['label' => 'Última visita', 'value' => '28 oct 2026'],
                    ['label' => 'Ticket habitual', 'value' => '$22.000'],
                ]],
                'jose-navarro' => ['meta' => 'Color raíz', 'note' => 'Pide el mismo estilista.', 'facts' => [
                    ['label' => 'Preferencia', 'value' => 'Silla 3'],
                    ['label' => 'Última visita', 'value' => '4 sep 2026'],
                    ['label' => 'Ticket habitual', 'value' => '$38.000'],
                ]],
            ],
        };

        if ($vertical === 'mechanic') {
            $people = [];
            foreach ($extra as $key => $row) {
                $people[] = array_merge($row, ['key' => $key]);
            }

            return $people;
        }

        $people = [];
        foreach ($shared as $key => $row) {
            $people[] = array_merge($row, $extra[$key] ?? ['meta' => '', 'note' => '', 'facts' => []], ['key' => $key]);
        }

        return $people;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function person(string $vertical, string $key): ?array
    {
        foreach (self::people($vertical) as $person) {
            if ($person['key'] === $key) {
                return $person;
            }
        }

        return null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function today(string $vertical): array
    {
        return array_values(array_filter(
            self::events($vertical),
            fn (array $event): bool => $event['day'] === 0,
        ));
    }
}
