# Foundation UI Components

## Proposito

Foundation UI Components v1 define una libreria Blade reusable, neutral y mobile-first para productos SaaS construidos sobre Meritech Foundation.

La libreria no representa un modulo de negocio. Su objetivo es evitar duplicacion visual y entregar patrones consistentes para layouts, navegacion, datos, formularios, acciones y feedback.

## Convencion De Nombres

Los componentes viven bajo `resources/views/components/foundation`.

Se consumen con el prefijo `x-foundation`:

```blade
<x-foundation.layout.app-shell>
    <x-foundation.card>
        Content
    </x-foundation.card>
</x-foundation.layout.app-shell>
```

Los grupos principales son:

- `x-foundation.layout.*`
- `x-foundation.navigation.*`
- `x-foundation.data.*`
- `x-foundation.forms.*`
- `x-foundation.actions.*`
- `x-foundation.feedback.*`

## Filosofia

- Blade primero.
- Tailwind primero.
- Alpine-friendly donde exista interaccion ligera.
- Sin paquetes nuevos.
- Sin Vue, React, Inertia, Livewire ni Jetstream.
- Sin componentes acoplados a productos consumidores.
- Sin consultas directas a base de datos desde componentes.

## Branding

`x-foundation.layout.app-shell` consume `App\Core\Branding\BrandingManager` y expone variables CSS por request:

- `--foundation-color-primary`
- `--foundation-color-secondary`
- `--foundation-color-background`
- `--foundation-color-surface`
- `--foundation-color-text`
- `--foundation-color-muted`

Los componentes usan esas variables para acciones primarias, estados activos y foco. Si no existe tenant o settings, `BrandingManager` entrega defaults seguros.

## React Islands Futuras

Foundation no instala React ni lo requiere en Core UI.

El contrato futuro de montaje se documenta en `docs/01_ARCHITECTURE/REACT-ISLANDS.md` y usa atributos `data-foundation-island` con props serializadas. Blade sigue siendo el renderer principal.

## Accesibilidad

Los componentes deben mantener defaults accesibles:

- estados de foco visibles;
- etiquetas o `aria-label` para controles icon-only;
- `aria-current="page"` en navegacion activa;
- `role="status"` en feedback no modal;
- `sr-only` para labels visualmente ocultos;
- contraste compatible con light/dark theme.

## Extension

Antes de crear un componente nuevo, revisar si existe uno reusable en `foundation`.

Crear un componente nuevo solo cuando:

- resuelve un patron repetido;
- no introduce lenguaje de negocio;
- puede ser usado por multiples productos;
- conserva soporte responsive y dark mode;
- documenta su convencion si agrega una categoria nueva.

## Ejemplos

```blade
<x-foundation.layout.page-header
    title="Settings"
    description="Global foundation settings."
/>
```

```blade
<x-foundation.actions.button>
    Save
</x-foundation.actions.button>
```

```blade
<x-foundation.data.empty-state
    title="No records"
    description="Try adjusting the current filters."
/>
```

```blade
<x-foundation.forms.input
    label="Name"
    name="name"
/>
```

## Fuera De Alcance

- Pantallas CRUD.
- Dashboards de negocio.
- Uploads.
- Branding editor UI.
- Selector visual de tenant.
- Billing.
- Feature flags.
- Dominios consumidores.
