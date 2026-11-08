# ADR-008: Aislamiento de datos por tenant

## Estado

Aprobada.

## Contexto

Tenancy v1 resolvía el tenant activo y lo limpiaba al final del request. Las consultas, la caché, los archivos y las colas no tenían ese límite. `tenant_settings` se podía leer completo sin contexto.

## Decision

Los modelos que pertenecen a un tenant usan `BelongsToTenant`.

- Sin contexto, el scope global no devuelve filas.
- Una escritura sin contexto, o con otro `tenant_id`, se rechaza.
- `TenantIsolation::bypass()` y `withoutGlobalScope(TenantScope::class)` son el opt-out explícito de plataforma.
- `TenantResolver` usa ese opt-out porque tiene que buscar membresías antes de existir un contexto, y las filtra por el usuario autenticado.
- La regla de una sola membresía default sigue cruzando tenants a propósito, también con opt-out.
- `TenantCache` prefija las llaves con el tenant y falla si no hay contexto.
- `TenantStorage` escribe bajo `tenants/{id}/` en el disco `foundation.tenancy.disk`.
- El payload de cada job guarda `foundation_tenant_id`. Al ejecutarse, el contexto se restaura y después se devuelve al valor anterior.
- Los jobs de dominio usan `RequiresTenantContext` para fallar si se despachan sin tenant.
- `foundation:tenant-check {tenant}` ata el contexto de un comando al tenant pedido.
- `tenant.resolve` corre después de `auth` y antes de `SubstituteBindings`, para que el scope ya exista cuando Laravel resuelve un modelo de la ruta.

`users` y `tenants` no llevan el scope. El usuario sigue siendo global.

## Consecuencias

Un producto que agregue citas, fichas o pagos debe usar el trait. Consultar el facade `Cache` o `Storage` directo sigue siendo una operación de plataforma y no hereda el prefijo.
