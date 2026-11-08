# ADR-007: Tokens de vertical y vistas de muestra

## Estado

Aprobada.

## Contexto

Foundation ya tiene componentes Blade, branding por tenant y Tailwind 3. Los productos que se apoyan en esta base necesitan un acento visual por vertical (servicios, salud, taller) sin copiar pantallas de negocio ni consultar `TenantSettings` desde los componentes.

## Decision

`x-foundation.layout.app-shell` sigue leyendo la marca del tenant mediante `BrandingManager` y acepta props opcionales `primary`, `secondary`, `accent`, `vertical` y `theme`.

- `--foundation-color-primary` representa la marca del tenant.
- `--foundation-color-accent` representa la vertical. Si no hay vertical, repite la marca.
- Los presets viven en `config/foundation.php` bajo `ui.verticals`. No son módulos ni feature flags por tenant.
- El modo oscuro usa la estrategia `class` de Tailwind. `dark` se aplica cuando el tema es `dark`; `system` lo resuelve el navegador.
- Las rutas `/ui/{vertical}` son una vista previa con datos ficticios en memoria. No crean modelos de citas, pacientes, vehículos ni pagos. Quedan apagadas salvo `FOUNDATION_UI_SHOWCASE=true`.

## Reutilizar o crear

Se reutilizan los componentes `x-foundation.*`. Se crea el catálogo `App\Ui\Showcase\PreviewCatalog` fuera de Core porque es material de demostración, no una capacidad transversal.

## Consecuencias

Un producto puede cambiar el acento sin duplicar el shell. La vista previa no sustituye aislamiento de datos, ficha clínica ni integración con Nexura.
