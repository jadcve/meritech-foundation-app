# ADR-001: Foundation nace como base agnostica

## Estado

Aprobada.

## Decision

Meritech Foundation nace como una base tecnologica agnostica del negocio, construida sobre Laravel y documentada antes de ampliar su superficie funcional.

Foundation no sera una extraccion directa ni una evolucion renombrada de ningun producto existente. Sera la base oficial para construir productos SaaS reutilizables dentro de Meritech.

## Contexto

Meritech necesita una plataforma base para crear productos SaaS con arquitectura comun. Pueden existir aprendizajes previos, pero Foundation debe evitar heredar nombres, acoplamientos o reglas de negocio de un producto especifico.

Partir desde una base limpia permite definir primero arquitectura, limites, reglas de colaboracion y decisiones, dejando la implementacion funcional para etapas posteriores.

## Consecuencias

- Foundation se trata como producto tecnologico propio.
- La documentacion precede a la funcionalidad nueva.
- El Core debe permanecer pequeno, estable y agnostico.
- Toda funcionalidad futura debe justificarse dentro de la vision modular.
- Las decisiones arquitectonicas relevantes se registran mediante ADR.
- Si hay duda entre reutilizar una convencion existente o crear una nueva, la decision debe documentarse.

