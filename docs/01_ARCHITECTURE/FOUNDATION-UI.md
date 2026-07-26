# Foundation UI Architecture

## Proposito

Foundation UI es la capa visual reutilizable de Meritech Foundation. Define componentes Blade, convenciones Tailwind e interacciones Alpine pequenas para productos SaaS futuros.

No contiene pantallas de negocio, reglas de dominio ni identidad de productos consumidores.

## Renderer Principal

Blade sigue siendo el renderer principal.

Las vistas se componen con componentes anonimos bajo:

```text
resources/views/components/foundation/
```

Familias actuales:

- `layout`
- `navigation`
- `data`
- `forms`
- `actions`
- `feedback`
- cards de superficie general

## Tailwind

Tailwind sigue siendo el sistema principal de estilos. Los componentes usan clases utilitarias y solo exponen tokens CSS minimos para branding y consistencia visual.

Tokens actuales expuestos por `x-foundation.layout.app-shell`:

- `--foundation-color-primary`
- `--foundation-color-secondary`
- `--foundation-color-background`
- `--foundation-color-surface`
- `--foundation-color-text`
- `--foundation-color-muted`
- `--foundation-radius-sm`
- `--foundation-radius-md`
- `--foundation-radius-lg`
- `--foundation-shadow-sm`
- `--foundation-shadow-md`

Los aliases `--foundation-primary` y `--foundation-secondary` se mantienen temporalmente por compatibilidad.

## Branding

El flujo permitido es:

```text
TenantContext
    -> BrandingManager
    -> CSS variables
    -> Blade, Alpine y futuras React Islands
```

Los componentes no consultan `TenantSettings` ni resuelven tenants.

Si no existe tenant activo o no existen settings, `BrandingManager` entrega defaults seguros.

## Alpine

Alpine es la capa de interaccion ligera por defecto.

Usos adecuados:

- dropdowns;
- modales;
- switches;
- menus;
- overlays simples;
- estado local de UI.

Alpine no debe resolver tenants, autorizar usuarios ni mantener estado global de negocio.

## Accesibilidad

Los componentes deben mantener:

- foco visible;
- botones y enlaces semanticos;
- labels o `aria-label`;
- `aria-current` en navegacion activa;
- `aria-haspopup` y `aria-expanded` en menus;
- `role="dialog"` y `aria-modal="true"` en modales;
- `role="status"` en feedback no modal;
- labels `sr-only` cuando el texto visual no corresponde.

Esta documentacion no declara cumplimiento WCAG total. La auditoria formal queda fuera de este sprint.

## Responsive

Los componentes son mobile-first y deben funcionar en:

- mobile;
- tablet;
- desktop;
- wide desktop.

Tablas y barras de filtros deben contemplar overflow horizontal o composicion flexible.

## Reglas De Extension

Antes de crear un componente nuevo:

1. Revisar si ya existe un componente Foundation equivalente.
2. Confirmar que el patron es reusable entre productos.
3. Evitar nombres de dominio.
4. Mantener soporte light/dark.
5. Mantener props y slots simples.
6. Agregar documentacion si se crea una nueva familia.

## Fuera De Alcance

- CRUDs.
- Dashboards de negocio.
- UI de roles o permisos.
- Selector visual de tenant.
- Uploads.
- Billing.
- Feature flags.
- SPA.
- Migracion a otro renderer.
