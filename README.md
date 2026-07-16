# Meritech Foundation

Meritech Foundation es la base tecnologica oficial para construir productos SaaS reutilizables dentro del ecosistema Meritech.

No es Nexura, Restaurant, Menu, CRM ni ERP. Tampoco es un framework independiente. Foundation es el punto de partida comun sobre el que Meritech podra crear, mantener y escalar productos digitales con una arquitectura consistente.

## Proposito

Foundation existe para separar la base tecnica reutilizable de los productos finales. Su responsabilidad es definir una estructura clara para futuros modulos, dominios, servicios compartidos y reglas de desarrollo, evitando que cada producto de Meritech vuelva a resolver los mismos problemas desde cero.

## Objetivos

- Establecer una arquitectura base para productos SaaS de Meritech.
- Mantener una separacion clara entre Core, Modules, Domains, Shared y Support.
- Permitir que nuevos productos se construyan con criterios comunes.
- Documentar decisiones antes de implementar funcionalidades.
- Reducir acoplamiento entre productos especificos y capacidades reutilizables.
- Proteger a Foundation de convertirse en una copia de un producto existente.

## Filosofia

Foundation parte desde Laravel limpio, pero no se define por Laravel. Laravel es el stack base; Foundation es la arquitectura, los acuerdos y la direccion tecnica que Meritech usara para construir productos.

La filosofia del producto es:

- Primero arquitectura, luego implementacion.
- Primero decisiones explicitas, luego codigo.
- Primero limites claros, luego modulos.
- Reutilizable no significa generico sin criterio.
- Cada modulo debe tener una razon real para existir.
- Los productos finales no deben contaminar el Core.

## Stack Base

- PHP
- Laravel
- Composer
- Node.js
- Vite
- Tailwind CSS
- MySQL o base de datos compatible segun producto

Este stack puede evolucionar, pero cualquier cambio relevante debe quedar registrado como decision arquitectonica.

## Vision

Meritech Foundation sera la plataforma interna que permita crear productos SaaS con velocidad, consistencia y control. Su valor no esta en implementar todo desde el primer dia, sino en definir una base que pueda crecer sin perder identidad.

Foundation debera permitir construir productos como:

- plataformas multiusuario;
- soluciones con roles y permisos;
- sistemas con configuracion por producto o cliente;
- productos con identidad visual configurable;
- aplicaciones con notificaciones, auditoria, medios, busqueda y pagos;
- soluciones SaaS reutilizables para distintos mercados.

## Roadmap Resumido

- Sprint 0: documentar vision, arquitectura, decisiones iniciales y reglas de desarrollo.
- Sprint 1: definir limites tecnicos del Core y convenciones base.
- Sprint 2: especificar catalogo de modulos candidatos sin implementarlos.
- Sprint 3: preparar criterios para iniciar implementacion modular controlada.

## Documentacion

La documentacion principal vive en:

- `docs/00_PRODUCT/`
- `docs/01_ARCHITECTURE/`
- `docs/02_DECISIONS/`
- `docs/03_MODULES/`
- `docs/04_DEVELOPMENT/`
- `docs/05_ROADMAP/`

Foundation comienza como una decision de producto y arquitectura. El codigo vendra despues.
