# Branding

Branding v1 permite que cada tenant tenga identidad y configuracion visual/formato sin introducir conceptos de negocio.

## Modelo

`TenantSettings` pertenece a `Tenant`.

Campos principales:

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

## Servicio

`BrandingManager` expone valores del tenant activo:

```php
app(BrandingManager::class)->primaryColor();
```

El servicio usa `TenantContext` como fuente unica de tenant y cachea `TenantSettings` durante el request.

## Fail-Safe

Si no hay settings, el servicio devuelve defaults seguros. Si no hay tenant activo, tambien evita fallar por acceso nulo.

## Uso Esperado

Las rutas o vistas tenant-aware deben ejecutarse despues de `tenant.resolve` para que `TenantContext` este activo.

Branding no resuelve tenants por su cuenta y no consulta autorizacion.

## Fuera De Alcance

- UI para modificar branding.
- Upload de logo o favicon.
- Selector de tenant.
- Settings de negocio.
- Billing, subscriptions o feature flags.
