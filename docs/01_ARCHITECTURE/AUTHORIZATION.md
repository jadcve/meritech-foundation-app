# Authorization

Authorization v1 define roles y permisos tenant-aware para Foundation usando Spatie Laravel Permission con teams.

## Modelo

Foundation mantiene tenancy y autorizacion separados:

- `TenantMembership` describe la relacion entre usuario y tenant.
- Spatie describe roles y permisos.
- `TenantContext` contiene el tenant resuelto.
- `TenantAuthorizationContext` sincroniza el tenant resuelto con el team context de Spatie.

La columna de team de Spatie se llama `tenant_id`.

## Roles Foundation

- `owner`
- `admin`
- `member`
- `viewer`

Estos roles son genericos. Los productos no deben agregar roles de negocio en Foundation.

## Permisos Foundation

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

Use `members.*` para acciones sobre usuarios dentro de un tenant.

## Rutas

Las rutas tenant-aware deben declarar el pipeline completo:

```php
Route::middleware([
    'auth',
    'verified',
    'tenant.resolve',
    'tenant.authorization',
    'permission:settings.view',
])->group(function () {
    // Tenant-scoped routes
});
```

`tenant.resolve` debe ejecutarse antes de `tenant.authorization`.

## Checks En Codigo

Un check como este solo es valido cuando el tenant y la autorizacion tenant-aware ya estan activos:

```php
abort_unless(auth()->user()->can('settings.update'), 403);
```

Sin tenant activo, los checks tenant-scoped deben fallar cerrado.

## Seeders

`AuthorizationSeeder` ejecuta:

- `FoundationPermissionSeeder`
- `FoundationRoleSeeder`

Los permisos Foundation se crean de forma idempotente. Los roles se crean por tenant existente y sincronizan los permisos definidos por Foundation.

Los seeders no asignan roles automaticamente a usuarios.

## Tests

Los tests deben:

- crear tenant y membership explicitamente;
- seedear permisos y roles Foundation;
- asignar roles dentro del team tenant correcto;
- limpiar el contexto de permisos cuando cambian de tenant;
- verificar que un tenant no hereda permisos de otro.

## Extension Por Modulos

Los modulos futuros podran registrar permisos adicionales mediante una convencion documentada, por ejemplo:

```php
final class ModulePermissions
{
    public const RESOURCE_VIEW = 'module.resource.view';
}
```

Authorization v1 no implementa discovery automatico de modulos. La extension debe ser explicita hasta que exista una ADR para modularidad.

## Global Versus Tenant

Los permisos de tenant no son permisos globales. Futuros roles globales como `platform.super-admin` deben vivir fuera de `TenantMembership` y no deben conceder permisos tenant-scoped de forma implicita.

`TenantBypassPolicy` no concede autorizacion.

## Fuera De Alcance

- UI de roles.
- UI de permisos.
- Selector visual de tenant.
- Tenant switcher.
- Roles de negocio.
- Permisos de negocio.
- Billing, subscription o feature flags.
