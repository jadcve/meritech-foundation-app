# Roadmap

## Fase 0: Base documental

- Completar documentacion de producto.
- Completar documentacion de arquitectura.
- Registrar ADR inicial.
- Crear reglas de desarrollo.
- Crear guias de agentes.

## Fase 1: Criterios tecnicos

- Definir convenciones por capa.
- Documentar criterios para ampliar Core.
- Preparar ADRs para modulos candidatos.
- Formalizar estrategia de pruebas.
- Cerrar Tenancy v1 con memberships explicitas, resolucion no arbitraria, middleware opt-in y fail-closed.

## Fase 2: Modularidad controlada

- Priorizar modulos reutilizables.
- Documentar contratos antes de implementar.
- Validar dependencias entre Core, Modules, Shared y Support.
- Evaluar selector de tenant, cambio por sesion y politicas de acceso despues de Tenancy v1.
- Mantener roles por tenant y permisos fuera de Tenancy v1 hasta ADR especifica.
- Cerrar Authorization v1 con Spatie teams, roles genericos Foundation, permisos genericos Foundation, fail-closed y pruebas de aislamiento.
- Cerrar Tenant Settings and Branding v1 con `TenantSettings`, `BrandingManager`, defaults seguros y pruebas de aislamiento.
- Consolidar Foundation UI Architecture Review con tokens minimos, accesibilidad base, Alpine ligero y boundary futuro para React Islands opt-in.
- Cerrar Capability System v1 con registry, config Foundation-level, contratos y ADR sin implementar capabilities concretas.

## Fase 3: Preparacion para productos consumidores

- Definir guias de adopcion.
- Documentar configuracion por producto.
- Preparar checklist de seguridad, tenancy y despliegue.
- Evaluar React Islands solo cuando exista una interaccion rica aprobada por ADR, sin convertir Foundation en SPA.
- Definir la primera capability concreta mediante ADR antes de implementar servicios o UI.
