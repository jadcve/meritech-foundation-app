# ADR-002: Tenancy v1

## Estado

Aprobada.

## Decision

Meritech Foundation adopta una primera version de multitenancy compartido en una sola base de datos.

Cada usuario puede pertenecer a multiples tenants mediante una membresia explicita. La tabla `users` no contiene una FK directa `tenant_id`.

## Modelo

`tenants` representa el tenant tecnico.

`tenant_memberships` representa la relacion entre usuario y tenant con estos campos minimos:

- `id`
- `tenant_id`
- `user_id`
- `is_active`
- `is_default`
- timestamps

La tabla debe tener una restriccion unica compuesta por `tenant_id` y `user_id`, ademas de indices utiles para resolver por usuario, estado activo y tenant.

No se incluyen en v1 roles por tenant, permisos, subscription, plan ni datos de billing.

## Resolucion

El resolver sigue este flujo:

1. Usuario autenticado.
2. Membresias activas.
3. Membresia default, cuando existen multiples membresias.
4. Tenant activo.
5. `TenantContext`.

Si el usuario tiene una unica membresia activa hacia un tenant activo, Foundation puede resolverla automaticamente.

Si el usuario tiene multiples memberships activas y ninguna default, Foundation no selecciona un tenant arbitrariamente.

## Fail-Closed

Las rutas que requieren tenant deben fallar cerrado cuando no se puede resolver un tenant activo.

La ausencia de tenant en rutas tenant-aware debe producir una respuesta 403 controlada mediante `TenantNotResolvedException`.

## Middleware

La resolucion de tenant es opt-in mediante el alias `tenant.resolve`.

No se ejecuta globalmente en todas las rutas web.

Las rutas de login, logout, recuperacion de password, verificacion de email y perfil global no requieren tenant.

## Aplazado

- Roles por tenant.
- Permisos.
- Selector visual de tenant.
- Cambio de tenant por sesion.
- Resolucion por subdominio.
- Resolucion por headers.
- Branding.
- Settings.
- Dominios de negocio.

## Consecuencias

Foundation conserva una base multi-tenant reusable sin introducir logica de negocio. Los productos consumidores podran definir seleccion visual, permisos y politicas especificas en fases posteriores.
