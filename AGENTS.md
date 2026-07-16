# AGENTS.md

## Proposito de Foundation

Meritech Foundation es la base tecnica reutilizable para productos SaaS de Meritech. No es un producto final, no contiene dominios de negocio y no debe adoptar reglas especificas de ningun consumidor.

## Como debe trabajar Codex

- Consultar primero `codebase-memory-mcp`.
- Confirmar la estructura mediante el MCP antes de recorrer archivos manualmente.
- Revisar y respetar las ADR vigentes.
- Mantener cambios acotados al objetivo solicitado.
- No instalar paquetes ni modificar dependencias sin instruccion explicita y ADR.
- No introducir logica de negocio especifica en Foundation.

## Core primero

Core contiene capacidades fundacionales pequenas, estables y transversales. Cualquier ampliacion de Core debe justificarse porque beneficia a multiples productos y no pertenece a un modulo, dominio o soporte tecnico.

## Reutilizar o crear

Si existe una convencion o estructura adecuada, se reutiliza. Si se crea una nueva, la decision debe documentarse con su razon.

