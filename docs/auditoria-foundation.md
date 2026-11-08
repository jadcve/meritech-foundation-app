# Auditoría técnica de Meritech Foundation

Fecha de ejecución: 8 de noviembre de 2026. Remediación: el mismo día, sobre el commit `d59bda6`.

## Estado tras la remediación

La base sigue sirviendo como esqueleto. Estos hallazgos de la lista priorizada quedaron cerrados en código y pruebas (99 pruebas, 326 aserciones):

1. Aislamiento de datos. `BelongsToTenant` oculta filas sin contexto y rechaza escrituras cruzadas. `TenantCache` prefija llaves. `TenantStorage` escribe en `tenants/{id}/`. Los jobs restauran `foundation_tenant_id` y `RequiresTenantContext` falla si no había tenant al despachar. `foundation:tenant-check` ata un comando al tenant pedido. La fuga de `tenant_settings` queda cubierta por `tests/Feature/Tenancy/TenantIsolationTest.php`. ADR-008.
2. `/register` responde 404 cuando `FOUNDATION_PUBLIC_REGISTRATION` es false.
3. `ProtectsSensitiveAttributes` cifra campos declarados en `$sensitive`. `audit_logs` guarda vistas y cambios por tenant, con el detalle cifrado, y la capability `audit` está encendida. Respaldo local en `docs/04_DEVELOPMENT/RESPALDO.md`. ADR-009.
4. Capabilities por tenant: override, luego plan de la suscripción activa, luego el default global. `servicios`, `salud` y `taller` nacen apagadas. El middleware y el gate `module` responden 403. `SubscriptionProvider` tiene implementación local, sin proveedor de pago. ADR-010.
5. `.github/workflows/ci.yml` corre Pint, la suite y `npm run build` en runners de GitHub. Se eliminó `@tailwindcss/vite`.

Sigue pendiente, y no entra en este cambio:

- Selector de tenant para un usuario con varias membresías.
- Índice único que impida dos membresías default por usuario.
- Actualizar `laravel/framework` y Symfony por los avisos de `composer audit`.
- Cifrar la sesión (`SESSION_ENCRYPT`) como decisión de despliegue.
- Un `SubscriptionProvider` que cobre de verdad. La interfaz ya existe; no hay que registrarse en un servicio de pago para usarla.
- Integración con Nexura y modelos de citas, fichas o vehículos. El trait está listo; esos modelos no pertenecen a Foundation.

## Veredicto original

Foundation sirve como base técnica, con condiciones. El esqueleto de Laravel, la autenticación, la tenencia por membresía, los permisos por tenant y el branding son reales y tienen pruebas.

En la primera pasada no estaba listo para guardar datos de salud ni para operar reservas. No había aislamiento automático de datos de producto, ni cifrado de campos sensibles, ni bitácora, ni respaldos, ni módulos por tenant, ni facturación. La sección anterior marca qué parte de eso ya está en el código.

Después de la corrección de este cambio, un `composer install --no-dev` sigue instalando `spatie/laravel-permission`. Antes, ese paquete estaba solo en `require-dev` y el arranque de producción quedaba roto.

## Cómo se verificó

`codebase-memory-mcp` no está disponible en este entorno. La revisión se hizo sobre el código, las ADR y comandos ejecutados.

| Comando | Resultado |
| --- | --- |
| `composer install` con PHP 8.4.27 | Completó. PHP 8.3.6 no alcanza: el lock exige Symfony 8.1 (`php >= 8.4.1`). |
| `php artisan test` en un árbol sin `.env` | La primera corrida falló por `APP_KEY` ausente. `tests/bootstrap.php` genera una llave al arrancar la suite. No queda commiteada. |
| `php artisan test` tras las correcciones | 83 pruebas, 270 aserciones, todas verdes. |
| `npm run build` | Verde. CSS ~55 kB. El pipeline activo es Tailwind **3.4.19** vía PostCSS. |
| `composer install --no-dev --dry-run` | Quitarían 34 paquetes de desarrollo. `spatie/laravel-permission` 8.5.0 permanece en `packages`. |
| Sonda de aislamiento | Con el contexto en el tenant A, `TenantSettings::query()` devolvió colores de A y de B. Caché y disco local tampoco separan tenants. `GET /register` respondió 200 con `public_registration` en false. |
| `php artisan db:seed` | Crea tres tenants de muestra, membresías y el rol `owner` para `test@example.com`. |
| UI de Nexura | `jadcve/nexura` y `jadcve/nexura-platform` no son visibles con el token de este entorno (el API responde que el repositorio no existe). Se estudió el frente público actual en `https://nexurapp.app/` (landing y login). No se modificó Nexura ni SazonaMenu. |

## Hallazgos por severidad

### Crítico

1. **No hay aislamiento real de datos de producto.** La tenencia resuelve un contexto de request y lo limpia al terminar (`app/Core/Tenancy/Services/TenantContext.php`, `app/Core/Tenancy/Middleware/ResolveTenant.php`). No existe un global scope, un trait `BelongsToTenant` ni políticas de Eloquent. La sonda lo confirmó: con el tenant A activo, la consulta sin scope a `tenant_settings` leyó las dos filas. Lo mismo vale para cualquier modelo futuro de citas, pacientes o pagos. Los tests de `tests/Feature/Tenancy/TenancyTest.php` cubren la resolución, no fugas de filas.

2. **`spatie/laravel-permission` estaba solo en `require-dev`.** `app/Models/User.php` usa `HasRoles` y `bootstrap/app.php` registra sus middlewares. Con `--no-dev` el autoload no encontraba esas clases. Corregido: pasó a `require` y el lock lo deja en `packages` (8.5.0, dentro de `^8.3`).

3. **No hay postura de seguridad para datos clínicos.** No hay casts `encrypted`, ni tabla de auditoría, ni comando de respaldo o restore. `config/capabilities.php` deja `audit` en `false`. `SESSION_ENCRYPT` está en false en `.env.example`. El disco `local` tiene `serve => true` (`config/filesystems.php`) y `logo_path` es un string libre, sin prefijo de tenant. Guardar historia clínica, recetas o resultados de Nexura en este estado sería un riesgo inaceptable.

### Alto

4. **El flag de registro público no hace nada.** `config/foundation.php` y `.env.example` definen `FOUNDATION_PUBLIC_REGISTRATION=false`, pero `routes/auth.php` publica `GET/POST /register` siempre. La sonda recibió 200 en `/register`.

5. **No hay CI/CD ni empaquetado de deploy.** No existe `.github/`, `.gitlab-ci.yml`, Dockerfile ni compose. El README todavía dice que la etapa es solo documental.

6. **Las capabilities no son módulos por tenant.** `app/Core/Capabilities/Registry/CapabilityRegistry.php` carga booleanos globales de `config/capabilities.php`. No hay fila por tenant, ni plan, ni activación de “salud” frente a “taller”.

7. **No hay billing ni suscripciones.** No está Cashier ni un modelo de plan. Las ADR 002 y 003 lo dejan fuera de la v1. Sigue siendo un hueco para el SaaS.

8. **Caché, colas y archivos no conocen al tenant.** `config/cache.php` usa un prefijo de aplicación. `database/migrations/0001_01_01_000002_create_jobs_table.php` es la tabla estándar, sin `tenant_id`. No hay jobs de dominio. Un job futuro que consulte sin scope vería todas las filas, y el `TenantContext` no sobrevive al worker porque es scoped al request.

9. **Avisos de seguridad en dependencias.** `composer audit` reportó 29 avisos en 8 paquetes, entre ellos uno crítico de `laravel/framework` (TokenGuard con credenciales de API no string) y varios de Symfony. No se actualizó el framework en este cambio: es un salto de superficie mayor y debe ir en un PR propio con pruebas. `npm audit` marca 9 avisos en dependencias de build (Vite/PostCSS), no en runtime PHP.

10. **La resolución de tenant no permite elegir sucursal ni organización.** `TenantResolver` usa la única membresía activa o exactamente una default. Si hay varias y ninguna default, falla cerrado (correcto). No hay subdominio, header ni selector. Un dueño con dos locales no puede cambiar de contexto.

### Medio

11. **Tailwind 3 y 4 conviven en el manifiesto, y el build igual funciona.** `package.json` declara `tailwindcss` `^3.1.0` y `@tailwindcss/vite` `^4.0.0`. El lock instala Tailwind 3.4.19 en la raíz y 4.3.2 anidado bajo `@tailwindcss/vite`. `vite.config.js` no usa el plugin v4. `resources/css/app.css` sigue con `@tailwind base/components/utilities` y `tailwind.config.js` es formato v3. `npm run build` terminó bien. El paquete v4 es una trampa para el siguiente cambio, no un build roto. No se quitó aquí para no reescribir el toolchain sin ADR.

12. **Una sola membresía default se garantiza en el modelo, no en la base.** `TenantMembership::booted` apaga las otras defaults al guardar. No hay índice único parcial. Dos escrituras concurrentes pueden dejar dos defaults y el resolver falla cerrado.

13. **El seeder de roles dependía de eventos de Eloquent.** Con `WithoutModelEvents`, Spatie no refrescaba la caché de permisos y `syncPermissions` lanzaba `PermissionDoesNotExist` en cuanto existían tenants. Se corrigió llamando `forgetCachedPermissions()` al cerrar el seeder de permisos y al entrar a cada tenant en el de roles.

14. **Cobertura.** Hay pruebas de auth, perfil, tenencia, autorización por team, branding, capabilities y render de componentes. No hay driver de cobertura de líneas (pcov/xdebug no formaba parte del entorno). No hay pruebas de jobs, storage ni de un scope que todavía no existe.

### Bajo

15. `/` sigue siendo la pantalla de bienvenida de Laravel. No bloquea la base.
16. No hay UI de roles. Está declarado fuera de alcance en la ADR-003.
17. `README.md` no describe el estado real del repositorio.

## Qué se puede reutilizar tal cual

- Resolución de tenant fail-closed, sin elegir el primero al azar: `TenantResolver`, `ResolveTenant`, `TenantBypassPolicy` (apagado y sin correos fijos).
- Autorización por team de Spatie: roles `owner`, `admin`, `member`, `viewer` y permisos genéricos en `FoundationPermissions`. Los tests demuestran que el admin del tenant A no actualiza settings cuando el contexto resuelto es el B.
- `BrandingManager` y `tenant_settings` (locale, zona, moneda, tema, colores, rutas de logo).
- Biblioteca Blade `x-foundation.*` (shell, navegación, cards, datos, formularios, feedback). Este cambio la viste; no la reemplaza.
- Registro de capabilities como contrato global, a la espera de una activación por tenant.
- Auth de Breeze ya publicada (login, reset, verificación, perfil). Breeze puede seguir en `require-dev`: no se importa en runtime.

## Correcciones hechas en este cambio

Solo piezas de bajo riesgo y efecto claro:

- `spatie/laravel-permission` pasó de `require-dev` a `require`. El lock quedó en 8.5.0.
- `phpunit.xml` deja la vista previa apagada. La suite genera `APP_KEY` en `tests/bootstrap.php` y CI también ejecuta `php artisan key:generate`.
- Los seeders de permisos olvidan la caché de Spatie para que `db:seed` funcione con eventos apagados.
- Tokens de vertical y modo `dark` por clase, más la vista previa `/ui/{vertical}` detrás de `FOUNDATION_UI_SHOWCASE` (default `false`).

No se implementó scope global, cifrado, bitácora, CI ni billing. Eso cambia el contrato de datos y merece diseño propio.

## Lista que sigue abierta

1. Selector de tenant cuando hay más de una membresía, sin romper el fail-closed.
2. Índice de base que impida dos defaults por usuario. Hoy lo garantiza el evento del modelo.
3. Actualizar `laravel/framework` y Symfony por los avisos de `composer audit`, en un PR dedicado.
4. Activar `SESSION_ENCRYPT` en el entorno que vaya a guardar datos sensibles.
5. Implementar un proveedor de suscripción real cuando exista un cobro. La interfaz `SubscriptionProvider` ya está; la implementación incluida es local.
6. Modelos de citas, fichas o vehículos en el producto que consuma Foundation, usando `BelongsToTenant`. La integración con Nexura sigue fuera.

## Sistema visual

Se tomó el frente público de Nexura como referencia, no como copia de producto:

- Tipografía Plus Jakarta Sans.
- Fondo slate con resplandor radial.
- Superficies blancas, radio amplio, sombra suave.
- Marca en pastilla con gradiente.
- Etiquetas en mayúsculas con tracking.
- Botones y campos con radio `2xl` y anillo de foco.

Foundation separa dos colores:

- `--foundation-color-primary`: marca del tenant (`BrandingManager` o prop).
- `--foundation-color-accent`: vertical (`services` rosa, `health` azul, `mechanic` naranja).

Las pantallas `/ui/services`, `/ui/health` y `/ui/mechanic` usan datos ficticios en memoria (`App\Ui\Showcase\PreviewCatalog`). No hay modelos de citas, pacientes, vehículos ni pagos. La ficha de salud muestra textos de muestra y dice que no es una integración. En producción la ruta responde 404 salvo `FOUNDATION_UI_SHOWCASE=true`.

La decisión está en `docs/02_DECISIONS/ADR-007-UI-VERTICAL-TOKENS.md`.
