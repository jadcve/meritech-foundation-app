# Contribuir a Meritech Foundation

Foundation se desarrolla con una regla principal: primero arquitectura, luego implementacion.

## Reglas Generales

- No introducir funcionalidades sin una definicion previa.
- No crear modulos sin documentar su alcance.
- No mezclar reglas de producto final con la base tecnologica.
- No usar nombres de productos existentes como identidad interna de Foundation.
- No asumir que todo debe vivir en Core.
- No instalar paquetes sin justificar la decision.
- No modificar el stack base sin registrar una decision arquitectonica.
- No agregar abstracciones sin necesidad real.

## Flujo Para Nuevas Capacidades

1. Definir el problema.
2. Determinar si pertenece a Core, Modules, Domains, Shared o Support.
3. Documentar el alcance.
4. Registrar una decision arquitectonica si cambia una regla relevante.
5. Definir criterios de aceptacion.
6. Implementar de forma pequena y verificable.
7. Agregar pruebas cuando exista comportamiento.

## Criterios Para Modulos

Un modulo puede avanzar a implementacion solo si:

- tiene un proposito claro;
- es reutilizable entre productos;
- no pertenece a un dominio de negocio especifico;
- tiene dependencias entendibles;
- puede evolucionar sin romper el Core;
- cuenta con documentacion minima.

## Criterios Para Core

Core debe mantenerse estable. Cualquier cambio en Core debe responder:

- que problema transversal resuelve;
- que alternativas existen;
- que impacto tiene sobre modulos y productos;
- que riesgo introduce;
- como se verificara.

## Documentacion

La documentacion es parte del producto. Todo cambio relevante debe actualizar los documentos correspondientes en `docs/`.

## Convenciones Iniciales

- Usar lenguaje claro y orientado a producto.
- Preferir decisiones pequenas y explicitas.
- Evitar dependencias innecesarias.
- Mantener compatibilidad con el stack base salvo decision registrada.
- Proteger la independencia entre Foundation y productos construidos sobre ella.
