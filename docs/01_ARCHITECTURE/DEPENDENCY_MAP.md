# Mapa de Dependencias

## Lectura del MCP

El grafo reporta relaciones relevantes entre:

- `Feature` hacia `Http`
- migraciones hacia `Http` y `Core`
- `Http` hacia `Core`
- pruebas hacia factories y capas de aplicacion

## Interpretacion

La arquitectura actual conserva una base Laravel reconocible, con una zona `Core` para capacidades fundacionales. La dependencia deseada a futuro es que los productos consuman modulos y Core, sin que Core dependa de dominios de producto.

## Regla

Las dependencias deben apuntar hacia capacidades mas estables. Si una pieza introduce vocabulario o comportamiento de negocio, no pertenece a Core.

