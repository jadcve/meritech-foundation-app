# Capability System

## Proposito

El Capability System es el mecanismo de extension tecnico de Meritech Foundation.

Una capability es una capacidad reusable y agnostica del negocio que puede ser compuesta por productos futuros sin acoplarlos al Core.

Foundation implementa en v1 solo la infraestructura:

- configuracion;
- registry;
- contratos;
- API de consulta;
- guias de extension.

No implementa capabilities concretas como notifications, media, localization, audit, search, QR, API, React Islands o PDF.

## Que Es Una Capability

Una capability representa un bloque tecnico reutilizable.

Ejemplos validos:

- notifications;
- media;
- localization;
- audit;
- search;
- qr;
- api;
- react-islands;
- pdf.

## Que No Es Una Capability

No son capabilities de Foundation:

- dominios de producto;
- pantallas CRUD;
- billing;
- subscriptions;
- reglas de negocio consumidoras;
- entidades especificas de un producto.

## Estructura

La infraestructura vive en:

```text
app/Core/Capabilities/
├── Contracts/
├── Registry/
└── Support/
```

La configuracion Foundation-level vive en:

```text
config/capabilities.php
```

## Registry

`CapabilityRegistry` es responsable de:

- registrar capabilities;
- consultar si una capability existe;
- consultar si esta habilitada;
- resolver metadata;
- listar todas las capabilities;
- listar solo las habilitadas.

Contrato:

```php
App\Core\Capabilities\Contracts\CapabilityRegistryContract
```

Implementacion:

```php
App\Core\Capabilities\Registry\CapabilityRegistry
```

API estatica de conveniencia:

```php
use App\Core\Capabilities\Support\Capability;

Capability::enabled('notifications');
Capability::has('media');
Capability::all();
```

## Configuracion

`config/capabilities.php` define flags Foundation-level.

Ejemplo:

```php
return [
    'notifications' => false,
    'media' => false,
    'localization' => false,
    'audit' => false,
    'search' => false,
    'react-islands' => false,
];
```

V1 no incluye overrides por tenant.

## Ciclo De Vida

1. Foundation carga `config/capabilities.php`.
2. `FoundationServiceProvider` registra `CapabilityRegistryContract`.
3. `CapabilityRegistry` inicializa las capabilities configuradas.
4. Futuras capabilities podran registrar metadata y servicios propios.
5. Productos futuros consultaran el registry para componer capacidades disponibles.

## Service Providers Futuros

Cada capability futura debe registrar sus servicios de forma independiente.

No se debe crear un provider monolitico que conozca todas las capabilities.

Contrato preparado:

```php
App\Core\Capabilities\Contracts\CapabilityProviderContract
```

## Contratos De Managers

Se preparan contratos para managers futuros:

- `NotificationManagerContract`
- `MediaManagerContract`
- `LocalizationManagerContract`

Estos contratos no tienen implementaciones concretas en v1.

## React Islands

React no esta instalado.

La capability `react-islands` queda representada como flag deshabilitado por defecto y puede activarse en una fase futura con ADR especifica.

El Core UI no depende de React.

## Reglas De Extension

Antes de agregar una capability concreta:

1. Crear o actualizar ADR.
2. Confirmar que es tecnica y reusable.
3. Evitar dominios de producto.
4. Registrar contratos antes de implementaciones.
5. Registrar servicios en provider propio.
6. Mantener defaults seguros.
7. Agregar pruebas del registry y del manager concreto.

## Fuera De Alcance En v1

- Notifications implementation.
- Media library.
- Localization runtime.
- React installation.
- QR generation.
- Search engine.
- Product modules.
- CRUD screens.
- Billing.
- Feature flags.
