# ADR-004: Tenant Settings and Branding v1

## Estado

Aprobada.

## Contexto

Foundation necesita exponer identidad y configuracion reusable por tenant sin convertir `tenants` en una tabla con muchas columnas opcionales ni mezclar branding con autorizacion.

Tenancy v1 ya provee `TenantContext` como fuente del tenant activo.

## Decision

Foundation agrega `TenantSettings` como modelo dedicado para configuracion de tenant y `BrandingManager` como servicio de lectura por request.

## Modelo De Datos

`tenant_settings` pertenece a un tenant y contiene:

- `tenant_id`
- `locale`
- `timezone`
- `currency`
- `theme`
- `primary_color`
- `secondary_color`
- `logo_path`
- `favicon_path`
- `date_format`
- `time_format`

`tenants` conserva identidad minima: `name`, `slug` e `is_active`.

## Arquitectura De Branding

`BrandingManager`:

- lee el tenant actual desde `TenantContext`;
- carga `TenantSettings`;
- cachea la configuracion durante el request;
- expone valores de branding y formato;
- devuelve defaults seguros cuando no hay settings.

## Separacion De Tenants

Settings vive separado de `tenants` para evitar columnas nullable acumuladas y permitir que la configuracion evolucione sin cambiar la entidad tecnica de tenancy.

## Fail-Safe

Si no existe tenant activo o no existe `TenantSettings`, Foundation devuelve valores genericos seguros:

- colores base;
- theme `system`;
- locale `en`;
- timezone `UTC`;
- currency `USD`;
- formatos `Y-m-d` y `H:i`;
- logo y favicon nulos.

## Fuera De Alcance

- Selector visual de tenant.
- UI de branding.
- Upload de archivos.
- Settings de negocio.
- Billing.
- Subscriptions.
- Feature flags.
- Resolucion de tenant por headers, subdominios o sesion.

## Consecuencias

Los productos futuros pueden consumir branding desde `BrandingManager` sin conocer el almacenamiento. Foundation mantiene branding, tenancy y authorization como responsabilidades separadas.
