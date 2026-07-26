# ADR-005: Foundation UI Components v1

## Estado

Aprobada.

## Contexto

Foundation ya cuenta con autenticacion, tenancy, autorizacion y branding/settings por tenant. Las vistas actuales provienen de Breeze y resuelven lo minimo, pero no existe una biblioteca comun para productos futuros.

Crear componentes reutilizables reduce duplicacion y evita que cada producto defina patrones visuales incompatibles.

## Decision

Foundation adopta una libreria de componentes Blade anonimos bajo `resources/views/components/foundation`.

La libreria es:

- business-agnostic;
- Blade-first;
- Tailwind-first;
- Alpine-friendly;
- mobile-first;
- accesible por defecto;
- compatible con light/dark theme;
- integrada con `BrandingManager`.

## Integracion Con Branding

El componente `x-foundation.layout.app-shell` consume `BrandingManager` y define variables CSS por request para los colores principales.

Los componentes no consultan la base de datos directamente. La lectura de settings queda encapsulada en `BrandingManager`.

Los tokens publicos consolidados usan el prefijo `--foundation-color-*`.

## Reutilizar O Crear

Se conserva Breeze como base de autenticacion y se agregan componentes Foundation anonimos para no crear clases PHP innecesarias.

La decision de crear el namespace `foundation` se toma para separar componentes reutilizables de los componentes Breeze existentes y evitar romper vistas auth actuales.

## Alcance

Incluye componentes para:

- layout;
- navegacion;
- cards;
- datos;
- formularios;
- acciones;
- feedback.

## Fuera De Alcance

- Modulos de negocio.
- Pantallas CRUD.
- Dashboards.
- Billing.
- Feature flags.
- Upload implementation.
- Drag and drop builders.
- Migracion a Vue, React, Inertia o Livewire.

## Consecuencias

Los productos futuros pueden componer interfaces con `x-foundation.*` sin duplicar patrones. Foundation conserva una superficie neutral y extensible sin introducir dependencias nuevas ni reglas de negocio.
