# ADR-009: Datos sensibles, bitácora y respaldo

## Estado

Aprobada.

## Decision

Los atributos sensibles se declaran en `$sensitive` y el trait `ProtectsSensitiveAttributes` les aplica casts `encrypted` o `encrypted:array`. La llave es `APP_KEY`. Foundation no define campos clínicos.

`audit_logs` registra quién vio o cambió un modelo dentro del tenant activo. El detalle queda cifrado. La capability `audit` queda encendida por defecto. Si un tenant la apaga, `AuditLogger` no escribe.

El respaldo es local: `foundation:backup` y `foundation:restore`. No hay servicio de pago. El procedimiento está en `docs/04_DEVELOPMENT/RESPALDO.md`.

## Consecuencias

Restaurar en otro entorno exige la misma `APP_KEY`. El archivo de respaldo contiene todos los tenants.
