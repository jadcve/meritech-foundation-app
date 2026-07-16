# Tenancy

Tenancy es una capacidad transversal de Foundation para identificar el contexto activo de un tenant durante una solicitud.

## Estado observado

El MCP localiza elementos de tenancy en `app/Core/Tenancy`, incluyendo contrato de resolucion, contexto activo, resolver, middleware y modelo base de tenant.

## Principios

- El tenant activo debe resolverse de forma explicita.
- El contexto debe poder limpiarse entre solicitudes.
- La ausencia de tenant debe tratarse como condicion controlada.
- Tenancy no debe asumir reglas de negocio del producto consumidor.
- Un usuario puede pertenecer a multiples tenants mediante membresias explicitas.
- `users` no debe tener una FK directa `tenant_id`.
- La resolucion v1 usa una membresia activa hacia un tenant activo.
- Si no se resuelve tenant para un usuario autenticado, Foundation falla cerrado.

## Limites

Foundation puede definir como se resuelve y expone el contexto tecnico de tenant. Los productos finales definen que significa un tenant para su negocio.

## Tenancy v1

La primera version usa:

- `Tenant` como entidad tecnica de tenant.
- `TenantMembership` como modelo real para la membresia entre usuario y tenant.
- `TenantResolver` para resolver una membresia activa unica o una membresia default explicita.
- `TenantContext` para exponer y limpiar el tenant activo durante el request.
- `ResolveTenant` como middleware de resolucion y fail-closed.

Tenancy v1 no usa `tenant_id` directo en `users`. La membresia minima contiene `id`, `tenant_id`, `user_id`, `is_active`, `is_default` y timestamps.

`tenant_memberships` mantiene `unique(tenant_id, user_id)` e indices de resolucion por usuario/estado/default y tenant/estado. Las migraciones no contienen logica de negocio.

Si un usuario tiene una sola membresia activa hacia un tenant activo, se resuelve automaticamente. Si tiene multiples membresias activas, debe existir exactamente una membresia default. Foundation no selecciona el primer tenant arbitrariamente.

El middleware `tenant.resolve` solo debe aplicarse a rutas que requieren tenant. Las rutas de autenticacion, recuperacion de password, verificacion de email y perfil global no dependen de tenant. La ruta temporal `/tenant/dashboard` valida el flujo `auth`, `verified`, `tenant.resolve`.

La deuda heredada de roles/permisos queda documentada: si existen dependencias o traits relacionados con roles, Tenancy v1 no debe ampliar su uso ni mezclar roles dentro de membresias.

Esta version no introduce seleccion manual de tenant, roles de negocio, billing, plan, subscription ni aislamiento automatico de datos de producto. El bypass de superadmin queda detras de `TenantBypassPolicy` y no usa correos hardcodeados.

La decision formal esta registrada en `docs/02_DECISIONS/ADR-002-TENANCY-V1.md`.
