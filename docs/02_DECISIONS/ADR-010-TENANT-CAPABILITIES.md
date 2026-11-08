# ADR-010: Módulos por tenant y suscripción local

## Estado

Aprobada.

## Decision

Una capability se resuelve así:

1. Override en `tenant_capabilities`.
2. Capability del plan de la suscripción activa.
3. Default global de `config/capabilities.php`.

`servicios`, `salud` y `taller` nacen apagadas. Un plan o un override las enciende por tenant.

El middleware `module` y el gate `module` responden 403 si el módulo no está habilitado para el tenant activo.

`SubscriptionProvider` es la interfaz. La implementación incluida es `LocalSubscriptionProvider`. No llama a ningún proveedor de pago y no guarda credenciales de un servicio externo.

## Consecuencias

ADR-002 dejó planes fuera de la v1. Este modelo no contradice esa base: sigue sin integrar un cobro. El producto que cobre de verdad implementa otro `SubscriptionProvider` sin cambiar el resolvedor.
