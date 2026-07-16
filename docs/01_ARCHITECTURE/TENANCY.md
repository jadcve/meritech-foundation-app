# Tenancy

Tenancy es una capacidad transversal de Foundation para identificar el contexto activo de un tenant durante una solicitud.

## Estado observado

El MCP localiza elementos de tenancy en `app/Core/Tenancy`, incluyendo contrato de resolucion, contexto activo, resolver, middleware y modelo base de tenant.

## Principios

- El tenant activo debe resolverse de forma explicita.
- El contexto debe poder limpiarse entre solicitudes.
- La ausencia de tenant debe tratarse como condicion controlada.
- Tenancy no debe asumir reglas de negocio del producto consumidor.

## Limites

Foundation puede definir como se resuelve y expone el contexto tecnico de tenant. Los productos finales definen que significa un tenant para su negocio.

