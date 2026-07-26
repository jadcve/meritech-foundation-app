# React Islands Readiness

## Decision

React no forma parte del Core UI actual.

Foundation mantiene Blade como renderer principal y Alpine como interaccion ligera por defecto. React queda como una capability futura, opt-in y limitada a islands puntuales.

## Contrato De Montaje

El boundary futuro usa atributos de datos:

```html
<div
    data-foundation-island="example"
    data-props='{}'
></div>
```

El mounting layer debe:

- montar solo islands registradas;
- leer props serializadas desde `data-props`;
- fallar de forma segura si el JSON es invalido;
- no controlar rutas;
- no reemplazar Blade;
- compartir CSS variables de Foundation;
- evitar carga global innecesaria.

## Boundary Actual

`resources/js/foundation/core/islands.js` expone:

- `registerIsland(name, mount)`
- `mountIslands(root = document)`
- `window.FoundationIslands.register`
- `window.FoundationIslands.mountAll`

No importa React ni otro renderer. Solo define el contrato minimo para que un producto futuro registre islands cuando exista una ADR aprobada.

## Responsabilidades

Blade:

- render principal;
- layouts;
- componentes reutilizables;
- contenido inicial accesible.

Alpine:

- interaccion ligera;
- estado local;
- menus, modales y controles simples.

React Islands futuras:

- widgets ricos aislados;
- montaje opt-in;
- props serializadas;
- sin routing global;
- sin render global.

## Prohibiciones

React Islands no deben:

- convertir Foundation en SPA;
- reemplazar Blade;
- controlar navegacion global;
- resolver tenants;
- consultar settings directamente;
- introducir dependencias sin ADR.
