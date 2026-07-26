# ADR-007: Capability System v1

## Estado

Aprobada.

## Contexto

Meritech Foundation ya cuenta con auth, tenancy, authorization, branding/settings y una base UI reutilizable.

El siguiente paso arquitectonico es permitir que Foundation evolucione como plataforma sin convertir Core en una coleccion de modulos de producto.

Las capabilities deben representar bloques tecnicos reutilizables que productos futuros puedan componer.

## Decision

Foundation agrega un Capability System v1 compuesto por:

- `config/capabilities.php`;
- `CapabilityRegistryContract`;
- `CapabilityRegistry`;
- `CapabilityDefinition`;
- API estatica `Capability`;
- contratos de managers futuros;
- contrato para providers de capabilities futuras.

El sistema no implementa capabilities concretas.

## Arquitectura

La infraestructura vive bajo:

```text
app/Core/Capabilities/
```

Estructura:

- `Contracts`
- `Registry`
- `Support`

`FoundationServiceProvider` registra el registry y carga la configuracion.

## Registro

Las capabilities se registran por nombre, estado enabled/disabled y metadata opcional.

Ejemplo:

```php
$registry->register('notifications', false);
```

API de consulta:

```php
Capability::enabled('notifications');
Capability::has('media');
Capability::all();
```

## Configuracion

`config/capabilities.php` contiene flags Foundation-level.

V1 no incluye:

- overrides por tenant;
- UI de configuracion;
- base de datos;
- cache distribuido;
- feature flags comerciales.

## Providers Futuros

Cada capability futura debe tener su propio provider o mecanismo de registro independiente.

Se evita un provider monolitico que conozca todas las capabilities.

## React Islands

React no se instala.

`react-islands` existe como capability deshabilitada por defecto para que una futura ADR pueda habilitar el flujo sin redisenar el registry.

## Alternativas Consideradas

Crear tabla de capabilities: rechazado en v1 porque no hay overrides por tenant ni administracion runtime.

Implementar notifications/media/localization ahora: rechazado porque el sprint pide solo infraestructura.

Usar feature flags como concepto principal: rechazado porque una capability es una extension tecnica, no una regla comercial.

Provider monolitico de capabilities: rechazado porque acoplaria Core a capacidades futuras.

## Consecuencias

Foundation gana un punto de extension pequeno y testeable.

Los productos futuros podran consultar capabilities sin conocer implementaciones.

Core sigue agnostico del negocio.

## Fuera De Alcance

- Notifications implementation.
- Media library.
- Localization.
- React installation.
- QR generation.
- Search engine.
- Product modules.
- CRUD screens.
- Billing.
- Subscriptions.
- Feature flags.
