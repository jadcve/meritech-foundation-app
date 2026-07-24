# Meritech Foundation

Meritech Foundation es la base tecnologica oficial para construir productos SaaS reutilizables dentro del ecosistema Meritech.

Foundation no es un producto final ni un dominio de negocio. Es el punto de partida comun para crear, mantener y escalar aplicaciones con una arquitectura consistente, documentada y controlada.

## Proposito

Foundation separa la base tecnica reutilizable de los productos finales. Su responsabilidad es definir estructura, principios, limites, decisiones y criterios de desarrollo antes de introducir funcionalidades.

## Reglas de trabajo

- Consultar primero `codebase-memory-mcp` para entender la estructura real del proyecto.
- Respetar las ADR vigentes antes de proponer cambios.
- Mantener la filosofia Core primero.
- No introducir logica de negocio especifica en Foundation.
- Preferir reutilizar convenciones existentes antes de crear nuevas.
- Documentar la decision cuando exista duda entre reutilizar o crear.

## Documentacion

- `docs/00_PRODUCT/`: vision, mision, principios y alcance.
- `docs/01_ARCHITECTURE/`: arquitectura, tenancy, mapas y brechas.
- `docs/02_DECISIONS/`: decisiones arquitectonicas.
- `docs/03_MODULES/`: catalogo conceptual de modulos.
- `docs/04_DEVELOPMENT/`: reglas de contribucion, pruebas y estandares.
- `docs/05_ROADMAP/`: evolucion esperada.
- `.agents/`: guias operativas por rol para asistentes y colaboradores.

## Estado actual

Esta etapa es documental. No define nuevas funcionalidades, modelos, migraciones, controladores, servicios, providers, JavaScript ni CSS.

