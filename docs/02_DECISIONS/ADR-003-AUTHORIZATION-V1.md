# ADR-003: Authorization v1

## Estado

Aprobada.

## Contexto

Foundation necesita autorizacion tenant-aware sin mezclar permisos con tenancy. Tenancy v1 ya resuelve un tenant activo mediante `tenant.resolve` y expone el resultado en `TenantContext`.

El paquete `spatie/laravel-permission` ya existe en el proyecto y `User` usa `HasRoles`.

## Decision

Authorization v1 usa Spatie Laravel Permission con teams habilitado.

El tenant resuelto es el contexto de team de Spatie. Foundation usa la columna `tenant_id` como `team_foreign_key` porque el contexto real es un tenant, no un equipo generico.

## Arquitectura

- `TenantMembership` conserva solo membresia entre usuario y tenant.
- `TenantContext` resuelve y conserva el tenant activo durante el request.
- `TenantAuthorizationContext` sincroniza `TenantContext` con Spatie.
- `tenant.authorization` activa y limpia el contexto de autorizacion.
- Las rutas tenant-aware deben ejecutar `tenant.resolve` antes de `tenant.authorization`.

## Integracion Con Tenant Context

El orden esperado es:

1. `auth`
2. `verified`
3. `tenant.resolve`
4. `tenant.authorization`
5. `permission:*` o `role:*`

Authorization no resuelve tenants por su cuenta y no selecciona tenants arbitrariamente.

## Modelo De Datos

Spatie administra:

- `permissions`
- `roles`
- `model_has_permissions`
- `model_has_roles`
- `role_has_permissions`

Con teams habilitado, `roles`, `model_has_permissions` y `model_has_roles` usan `tenant_id`.

`tenant_memberships` no contiene `role`, `role_id`, `permission`, `permissions`, `is_admin` ni `is_owner`.

## Modelo De Roles

Foundation define roles genericos:

- `owner`
- `admin`
- `member`
- `viewer`

Los roles se crean por tenant existente. No se asignan automaticamente a usuarios.

## Modelo De Permisos

Foundation define permisos genericos:

- `tenant.view`
- `tenant.update`
- `members.view`
- `members.invite`
- `members.update`
- `members.remove`
- `roles.view`
- `roles.manage`
- `settings.view`
- `settings.update`

Los productos o modulos futuros pueden registrar permisos adicionales sin modificar los permisos base de Foundation.

## Fail-Closed

Authorization v1 niega acceso cuando:

- no hay tenant resuelto;
- `TenantContext` esta vacio;
- no se activo el contexto de team de Spatie;
- el usuario no tiene membresia activa en el tenant resuelto;
- el usuario no tiene rol o permiso tenant-scoped;
- una ruta con autorizacion tenant-aware omite `tenant.resolve`.

## Global Versus Tenant Authorization

Los roles tenant-scoped son distintos de futuros roles globales de plataforma.

`TenantBypassPolicy` no concede permisos. Solo puede permitir comportamiento de infraestructura alrededor de resolucion de tenant. Un bypass no implica `can('*')`.

Authorization v1 no implementa asignacion de roles globales de plataforma. Si existe un rol conceptual como `platform.support`, no se asigna al usuario ni concede permisos tenant-scoped en este sprint.

## Alternativas Consideradas

- Guardar roles en `tenant_memberships`: rechazado porque mezcla tenancy y autorizacion.
- Crear un motor propio de permisos: rechazado porque Spatie ya cubre roles, permisos y teams.
- Resolver tenant desde headers, sesion o query params: rechazado porque ampliaria Tenancy v1.
- Registrar `tenant.resolve` globalmente: rechazado por acoplar rutas auth/globales a tenancy.

## Consecuencias

- Las rutas tenant-aware deben declarar explicitamente su pipeline.
- Las pruebas deben activar tenant y autorizacion cuando validan permisos.
- Los roles por tenant existen en tablas Spatie, no en `tenant_memberships`.
- La UI de administracion de roles queda fuera de alcance.

## Consideraciones De Seguridad

- No debe existir filtracion cross-tenant de roles o permisos.
- No hay fallback al primer tenant, ultimo tenant, tenant default arbitrario ni permisos globales silenciosos.
- El contexto de autorizacion se limpia despues del request.
- Las rutas sin tenant resuelto fallan cerrado.

## Fuera De Alcance

- Selector visual de tenant.
- Cambio de tenant por sesion.
- UI de roles o permisos.
- Invitaciones UI.
- Roles o permisos de negocio.
- Subdominios.
- Headers de resolucion.
- ABAC o rule builder.

## Requisitos De Prueba

Las pruebas deben cubrir aislamiento entre tenants, ausencia de membership, ausencia de contexto, roles default, permisos directos tenant-scoped, bypass sin permisos y orden de middleware.
