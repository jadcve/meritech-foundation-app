# ADR-006: Foundation UI Architecture Review

## Estado

Aprobada.

## Contexto

Foundation ya cuenta con Auth Baseline, Tenancy v1, Authorization v1, Tenant Settings and Branding v1 y Foundation UI Components v1.

La capa UI necesita consolidar responsabilidades visuales y dejar una ruta clara para interacciones ricas futuras sin convertir la aplicacion en SPA.

## Decision

Foundation conserva Blade como renderer principal, Tailwind como sistema de estilos y Alpine como capa de interaccion ligera.

React queda documentado como capability futura opt-in mediante islands. No se instala React en este sprint.

## Arquitectura UI

Los componentes viven bajo:

```text
resources/views/components/foundation/
```

La estructura se organiza por responsabilidad:

- layout;
- navigation;
- data;
- forms;
- actions;
- feedback;
- superficies generales.

No se mueven archivos solo por estetica.

## Blade

Blade renderiza layouts, navegacion, formularios, feedback y superficies reutilizables.

Los componentes deben ser business-agnostic y no deben resolver tenants, autorizar usuarios ni consultar Eloquent directamente.

## Alpine

Alpine cubre interacciones pequenas:

- dropdowns;
- modales;
- switches;
- overlays;
- estado local de UI.

No administra estado global de negocio.

## Tailwind

Tailwind sigue siendo el lenguaje principal de estilos. Foundation evita duplicar Tailwind completo como variables CSS.

Solo se exponen tokens minimos para branding y consistencia:

- colores principales;
- background;
- surface;
- text;
- muted;
- radios;
- sombras.

## Branding

El flujo permitido es:

```text
TenantContext
    -> BrandingManager
    -> CSS variables
    -> Blade / Alpine / React Islands futuras
```

Los componentes no consultan `TenantSettings` directamente.

## React Islands

React sera opt-in en una fase futura.

El contrato preparado usa:

```html
<div data-foundation-island="example" data-props="{}"></div>
```

El mounting layer registra islands por nombre y las monta solo cuando existen en la pagina.

React no controlara routing, no reemplazara Blade y no renderizara globalmente la aplicacion.

## Alternativas Consideradas

Adoptar SPA completa: rechazada porque aumenta complejidad y contradice Blade-first.

Instalar React ahora: rechazada porque no existe widget real aprobado.

Crear un design-token system extenso: rechazado porque duplicaria Tailwind sin necesidad.

Mover toda la estructura de componentes: rechazado porque la estructura actual ya es clara.

## Accesibilidad

Se agregan o conservan atributos criticos:

- `aria-current` para navegacion activa;
- `aria-haspopup` y `aria-expanded` para triggers de menu;
- `role="menu"` para dropdown content;
- `role="dialog"` y `aria-modal` para modales;
- `role="status"` para feedback.

No se declara cumplimiento WCAG total sin auditoria formal.

## Seguridad

Los componentes no deben introducir consultas directas, autorizacion, resolucion de tenant ni datos de negocio.

Las props serializadas para islands deben parsearse de forma segura y fallar a objeto vacio cuando sean invalidas.

## Fuera De Alcance

- Modulos de negocio.
- CRUDs.
- Dashboards.
- Charts.
- Calendars.
- Drag and drop.
- Rich text editor.
- React widgets de producto.
- SPA.
- Inertia.
- Next.js.
- Remix.
- Vue.
- Billing.
- Subscriptions.
- Uploads.
- Tenant selector.
- Roles UI.
- Permissions UI.
- Feature flags.
- Microfrontends.

## Consecuencias

Foundation mantiene simplicidad Laravel Blade, mejora la consistencia de UI y queda preparada para islands futuras sin asumir React como dependencia Core.
