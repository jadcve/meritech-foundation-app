# ADR-001: Foundation nace desde Laravel limpio

## Estado

Aprobada.

## Decision

Nexura permanecera independiente y Meritech Foundation nace desde Laravel limpio.

Foundation no sera una extraccion directa de Nexura ni una evolucion renombrada de ningun producto existente. Sera la base tecnologica oficial para construir productos SaaS reutilizables dentro de Meritech.

## Contexto

Meritech necesita una plataforma base para construir productos SaaS con arquitectura comun. Existen productos o ideas previas que pueden inspirar aprendizajes, pero Foundation debe evitar heredar decisiones, nombres, acoplamientos o reglas de negocio de un producto especifico.

Partir desde Laravel limpio permite definir primero la arquitectura, luego los modulos y finalmente las implementaciones necesarias.

## Ventajas

- Evita arrastrar deuda tecnica de productos existentes.
- Mantiene a Nexura independiente.
- Permite definir una identidad propia para Foundation.
- Reduce el riesgo de mezclar base tecnologica con producto final.
- Facilita documentar decisiones antes de codificar.
- Permite construir modulos reutilizables con limites claros.

## Riesgos

- Puede tomar mas tiempo llegar a funcionalidades visibles.
- Requiere disciplina para no copiar estructuras de productos existentes sin revision.
- Puede generar duplicacion temporal entre Foundation y productos independientes.
- Exige documentacion constante para evitar ambiguedades.

## Consecuencias

- Foundation se tratara como producto tecnologico propio.
- Nexura no sera migrado automaticamente a Foundation.
- El repositorio debe dejar de presentarse como proyecto Laravel generico.
- Las primeras tareas deben ser documentales y arquitectonicas.
- Toda funcionalidad futura debe justificarse dentro de la vision modular de Foundation.
- Las decisiones arquitectonicas relevantes se registraran mediante ADRs.
