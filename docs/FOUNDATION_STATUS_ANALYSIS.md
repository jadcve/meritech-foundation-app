# Meritech Foundation - Status Analysis

## Proposito Del Documento

Este documento resume el estado actual de Meritech Foundation para analisis tecnico, revision de arquitectura y preparacion del siguiente sprint.

No define nueva funcionalidad. Registra lo construido, las decisiones vigentes, los riesgos conocidos y las validaciones pendientes.

## Estado General

Meritech Foundation es una base tecnica SaaS construida sobre Laravel. Su objetivo es entregar capacidades reutilizables, agnosticas del negocio y preparadas para productos futuros.

Foundation no debe contener dominios consumidores como restaurant, laboratory, clinic, billing, subscriptions, menu builders ni pantallas CRUD especificas.

## Sprints Ejecutados

### 1. Documentacion Base

Se creo la estructura documental inicial:

- `docs/00_PRODUCT`
- `docs/01_ARCHITECTURE`
- `docs/02_DECISIONS`
- `docs/03_MODULES`
- `docs/04_DEVELOPMENT`
- `docs/05_ROADMAP`
- `.agents`
- `AGENTS.md`

Resultado:

- Vision, mision, principios y alcance documentados.
- Arquitectura base documentada.
- Reglas de desarrollo definidas.
- ADR inicial creada.
- Agentes por responsabilidad creados.

### 2. Auth Baseline

Se implemento autenticacion base con Laravel Breeze, Blade, Tailwind y Alpine.

Capacidades:

- registro;
- login;
- logout;
- recuperacion de password;
- cambio de password;
- verificacion de email;
- perfil basico;
- rutas protegidas con `auth` y `verified`;
- pruebas automatizadas.

Estado observado en navegador:

- `/` carga la pantalla publica default de Laravel.
- `/register` permite crear usuario.
- `/verify-email` aparece correctamente despues del registro si el usuario no esta verificado.
- `/dashboard` requiere usuario autenticado y verificado.

### 3. Tenancy v1

Se implemento tenancy compartido en una sola base de datos.

Piezas principales:

- `Tenant`
- `TenantMembership`
- `TenantContract`
- `TenantResolverContract`
- `TenantContext`
- `TenantResolver`
- `ResolveTenant`
- `TenantNotResolvedException`

Reglas vigentes:

- un usuario puede pertenecer a multiples tenants;
- la pertenencia se modela con membership explicita;
- no existe FK directa `tenant_id` en `users`;
- solo una membership default por usuario;
- si hay multiples tenants sin default, no se elige uno arbitrariamente;
- `tenant.resolve` es opt-in;
- rutas auth y perfil global no requieren tenant;
- rutas tenant-aware fallan cerrado con 403 si no hay tenant resoluble.

Comportamiento observado:

```json
{
  "message": "A tenant is required for this route, but no active tenant membership could be resolved."
}
```

Este comportamiento es correcto cuando se accede a rutas como:

- `/tenant/dashboard`
- `/tenant/settings`

con un usuario sin tenant membership activa/default.

### 4. Authorization v1

Se implemento autorizacion tenant-scoped usando Spatie Permission con teams.

Piezas principales:

- `FoundationPermissions`
- `TenantAuthorizationContext`
- `ActivateTenantAuthorization`
- seeder de permisos/roles Foundation;
- rutas tenant settings protegidas por permission middleware.

Roles Foundation genericos:

- `owner`
- `admin`
- `member`
- `viewer`

Permisos Foundation genericos:

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

Reglas vigentes:

- los permisos son tenant-scoped;
- permisos globales no otorgan permisos tenant;
- authorization requiere tenant context activo;
- si falta contexto, falla cerrado;
- no se implemento UI de roles ni permisos.

### 5. Tenant Settings And Branding v1

Se agrego configuracion reusable por tenant.

Piezas principales:

- `TenantSettings`
- `tenant_settings`
- `BrandingManager`
- relacion `Tenant::settings()`

Settings actuales:

- locale;
- timezone;
- currency;
- theme;
- primary color;
- secondary color;
- logo path;
- favicon path;
- date format;
- time format.

Reglas:

- los componentes no consultan `TenantSettings`;
- branding se consume mediante `BrandingManager`;
- si no hay tenant o settings, se usan defaults seguros;
- no hay upload de logo/favicon;
- no hay UI de branding;
- no hay settings de negocio.

### 6. Foundation UI Components v1

Se creo una libreria de componentes Blade anonimos.

Ubicacion:

```text
resources/views/components/foundation/
```

Familias:

- layout;
- navigation;
- data;
- forms;
- actions;
- feedback;
- cards/surfaces.

Componentes principales:

- `x-foundation.layout.app-shell`
- `x-foundation.layout.sidebar`
- `x-foundation.layout.topbar`
- `x-foundation.layout.page-header`
- `x-foundation.layout.breadcrumbs`
- `x-foundation.navigation.nav-item`
- `x-foundation.navigation.nav-group`
- `x-foundation.card`
- `x-foundation.stat-card`
- `x-foundation.metric-card`
- `x-foundation.action-card`
- `x-foundation.data.table`
- `x-foundation.data.empty-state`
- `x-foundation.forms.input`
- `x-foundation.forms.select`
- `x-foundation.forms.textarea`
- `x-foundation.actions.button`
- `x-foundation.feedback.alert`
- `x-foundation.feedback.modal`

Integracion:

- `resources/views/layouts/app.blade.php` usa `x-foundation.layout.app-shell`.
- `resources/views/layouts/guest.blade.php` usa `BrandingManager` para el title.

### 7. Foundation UI Architecture Review v1

Se consolido la arquitectura visual.

Decisiones:

- Blade sigue siendo el renderer principal.
- Tailwind sigue siendo el sistema principal de estilos.
- Alpine sigue siendo la capa ligera de interaccion.
- React queda como capability futura opt-in.
- No se adopta SPA.
- No se instala React.
- React no controla routing ni render global.

Tokens expuestos por `app-shell`:

- `--foundation-color-primary`
- `--foundation-color-secondary`
- `--foundation-color-background`
- `--foundation-color-surface`
- `--foundation-color-text`
- `--foundation-color-muted`
- `--foundation-radius-sm`
- `--foundation-radius-md`
- `--foundation-radius-lg`
- `--foundation-shadow-sm`
- `--foundation-shadow-md`

Aliases temporales:

- `--foundation-primary`
- `--foundation-secondary`

Boundary futuro para React Islands:

```html
<div
    data-foundation-island="example"
    data-props="{}"
></div>
```

Archivo preparado:

```text
resources/js/foundation/core/islands.js
```

Este archivo no importa React. Solo define el contrato de montaje futuro.

## Rutas Actuales Relevantes

Publicas:

- `GET /`
- `GET /login`
- `POST /login`
- `GET /register`
- `POST /register`
- `GET /forgot-password`
- `POST /forgot-password`
- `POST /reset-password`

Usuario autenticado:

- `GET /dashboard`
- `GET /profile`
- `PATCH /profile`
- `DELETE /profile`
- `POST /logout`

Tenant-aware:

- `GET /tenant/dashboard`
- `GET /tenant/settings`
- `PATCH /tenant/settings`

`/tenant/dashboard` requiere:

- `auth`
- `verified`
- `tenant.resolve`

`/tenant/settings` requiere:

- `auth`
- `verified`
- `tenant.resolve`
- `tenant.authorization`
- permiso `settings.view` o `settings.update`.

## Estado Del Working Tree

Al momento de este documento existen cambios pendientes relacionados con:

- ajustes de Pint en authorization;
- componentes Foundation UI;
- docs de UI architecture;
- boundary JavaScript de islands;
- layouts app/guest;
- pruebas UI.

Archivos destacados modificados o creados:

- `app/Core/Authorization/FoundationPermissions.php`
- `app/Core/Authorization/TenantAuthorizationContext.php`
- `bootstrap/app.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/guest.blade.php`
- `resources/views/components/dropdown.blade.php`
- `resources/views/components/modal.blade.php`
- `resources/views/components/foundation/**`
- `resources/js/app.js`
- `resources/js/foundation/core/islands.js`
- `tests/Feature/Ui/FoundationComponentsTest.php`
- `docs/UI_COMPONENTS.md`
- `docs/01_ARCHITECTURE/FOUNDATION-UI.md`
- `docs/01_ARCHITECTURE/REACT-ISLANDS.md`
- `docs/02_DECISIONS/ADR-005-FOUNDATION-UI-V1.md`
- `docs/02_DECISIONS/ADR-006-FOUNDATION-UI-ARCHITECTURE-REVIEW.md`
- `docs/05_ROADMAP/ROADMAP.md`

## Pruebas Existentes

Suites principales:

- Auth
- Profile
- Foundation config
- Tenancy
- Authorization
- Branding
- UI components

Prueba nueva relevante:

```text
tests/Feature/Ui/FoundationComponentsTest.php
```

Cubre:

- render de componentes;
- branding desde `BrandingManager`;
- defaults sin tenant settings;
- layout guest con defaults Foundation;
- markup critico de Alpine;
- ausencia de dependencia React.

## Validaciones Recientes

Ejecutado en la sesion de Codex:

- `git diff --check`: OK.
- revision de referencias de dominio prohibido: sin implementaciones detectadas.

Ejecutado por el usuario:

- `./vendor/bin/pint --test`

Resultado inicial:

- 4 style issues.

Resultado posterior:

- 1 style issue restante en `AuthorizationTest.php` por `braces_position`.

Accion aplicada:

- se ajusto la posicion de braces en anonymous class;
- se elimino import no usado de `TenantContext`;
- se mantuvo `new class extends TenantBypassPolicy`.

Validacion pendiente recomendada:

```powershell
./vendor/bin/pint --test
herd php artisan test
npm run build
composer validate
herd php artisan route:list
```

## Como Levantar Localmente

Servidor Laravel:

```powershell
cd C:\Users\jadcv\Herd\meritech-foundation-app
herd php artisan serve
```

URL esperada:

```text
http://localhost:8000
```

Servidor Vite para desarrollo:

```powershell
npm run dev
```

Build de assets:

```powershell
npm run build
```

Dominio Herd `.test`:

```text
http://meritech-foundation-app.test
```

Si falla con `DNS_PROBE_FINISHED_NXDOMAIN`, Herd no esta resolviendo el dominio. Revisar que la carpeta `C:\Users\jadcv\Herd` este registrada como parked path en Laravel Herd.

## Flujo Local De Prueba Manual

1. Abrir:

```text
http://localhost:8000/register
```

2. Crear usuario.

3. Si aparece `/verify-email`, verificar el correo o marcarlo manualmente en Tinker.

4. Para verificar manualmente:

```powershell
herd php artisan tinker
```

```php
$user = App\Models\User::first();
$user->forceFill(['email_verified_at' => now()])->save();
exit
```

5. Abrir:

```text
http://localhost:8000/dashboard
```

6. Para probar rutas tenant-aware, crear tenant y membership:

```powershell
herd php artisan tinker
```

```php
$user = App\Models\User::first();

$tenant = App\Core\Tenancy\Models\Tenant::create([
    'name' => 'Demo Tenant',
    'slug' => 'demo-tenant',
    'is_active' => true,
]);

App\Core\Tenancy\Models\TenantMembership::create([
    'tenant_id' => $tenant->id,
    'user_id' => $user->id,
    'is_active' => true,
    'is_default' => true,
]);

exit
```

7. Abrir:

```text
http://localhost:8000/tenant/dashboard
```

## Riesgos Tecnicos Reales

### 1. UI Aun Se Ve Como Laravel Default En `/`

La ruta publica `/` sigue usando la pantalla default de Laravel. Esto no rompe Foundation, pero puede confundir durante demos.

No se ha creado landing ni dashboard de producto porque estaria fuera de alcance.

### 2. Rutas Tenant-Aware Fallan Para Usuarios Sin Membership

Esto es comportamiento correcto, pero requiere datos seed/demo para pruebas manuales.

Debe mantenerse fail-closed.

### 3. Verificacion De Email Puede Bloquear El Dashboard

`/dashboard` usa `verified`. En local se debe configurar mail/log o marcar el usuario como verificado durante pruebas.

### 4. React Islands Tiene Boundary Tecnico, Pero No Uso Real

Existe un contrato minimo, sin React instalado. Es correcto para readiness, pero no debe ampliarse sin ADR y caso de uso aprobado.

### 5. Componentes UI Son Base, No Sistema Visual Final

La libreria ya existe, pero aun requiere uso real en pantallas Foundation futuras para validar ergonomia, responsividad y accesibilidad con contenido real.

## Decisiones Arquitectonicas Vigentes

ADRs relevantes:

- `ADR-001-FOUNDATION.md`
- `ADR-002-TENANCY-V1.md`
- `ADR-003-AUTHORIZATION-V1.md`
- `ADR-004-TENANT-SETTINGS-V1.md`
- `ADR-005-FOUNDATION-UI-V1.md`
- `ADR-006-FOUNDATION-UI-ARCHITECTURE-REVIEW.md`

Reglas clave:

- Foundation permanece agnostico del negocio.
- Core primero, pero Core debe mantenerse pequeno.
- `tenant.resolve` es opt-in.
- No se elige tenant arbitrariamente.
- Authorization tenant-scoped requiere tenant context.
- Branding se consume por `BrandingManager`.
- Blade es renderer principal.
- Alpine es interaccion ligera.
- React sera opt-in futuro.
- No SPA.

## Recomendacion Para El Siguiente Paso

Antes de iniciar otro sprint:

1. Confirmar que Pint esta limpio.
2. Ejecutar suite completa.
3. Ejecutar build.
4. Crear datos demo tecnicos si se quiere probar tenant-aware localmente.
5. Decidir si se conserva la pagina `/` default o si se reemplaza por una pagina tecnica Foundation, sin branding de negocio ni marketing pesado.

Orden recomendado:

```powershell
./vendor/bin/pint --test
herd php artisan test
npm run build
composer validate
herd php artisan route:list
```

Si todo pasa, el repo queda listo para commit/PR del bloque UI Architecture Review.
