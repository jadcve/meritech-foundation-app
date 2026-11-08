# Respaldo y restauración

Foundation guarda el respaldo en un archivo local. No usa servicios de pago ni almacenamiento externo.

## SQLite

El comando copia el archivo configurado en `DB_DATABASE`. Una base `:memory:` no se puede copiar.

```bash
php artisan foundation:backup storage/app/backups/foundation.sqlite
php artisan foundation:restore storage/app/backups/foundation.sqlite --force
```

`foundation:restore` reemplaza el archivo actual. `--force` es obligatorio.

## MySQL o Postgres

`foundation:backup` llama a `mysqldump` o `pg_dump` si el binario está instalado en el servidor. La contraseña viaja en el argumento del proceso local y no se imprime.

La restauración de esos motores se hace con el cliente de la base, por ejemplo:

```bash
mysql -h 127.0.0.1 -u foundation -p foundation < storage/app/backups/foundation.sql
psql "$DATABASE_URL" -f storage/app/backups/foundation.sql
```

## Qué incluye

El archivo es una copia de la base completa, así que mezcla los tenants en un solo respaldo cifrado por el disco del servidor, no por tenant. Los campos marcados con `ProtectsSensitiveAttributes` quedan cifrados dentro de esa copia con `APP_KEY`. Restaurar en otro entorno exige la misma `APP_KEY` para poder leerlos.

Guarda el archivo fuera del document root. `storage/app/private` es el lugar previsto. No lo subas al repositorio.
