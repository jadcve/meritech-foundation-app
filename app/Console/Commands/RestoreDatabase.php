<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RestoreDatabase extends Command
{
    protected $signature = 'foundation:restore {path : Backup file to restore} {--force : Replace the current database file}';

    protected $description = 'Restore a local database backup over the configured SQLite file.';

    public function handle(): int
    {
        if (! $this->option('force')) {
            $this->error('Restore overwrites the current database. Re-run with --force.');

            return self::FAILURE;
        }

        $source = $this->argument('path');

        if (! is_file($source)) {
            $this->error('Backup file not found.');

            return self::FAILURE;
        }

        $driver = config('database.connections.'.config('database.default').'.driver');

        if ($driver !== 'sqlite') {
            $this->error('Automatic restore is implemented for SQLite files. For MySQL or Postgres, load the dump with the database client described in docs/04_DEVELOPMENT/RESPALDO.md.');

            return self::FAILURE;
        }

        $destination = (string) config('database.connections.'.config('database.default').'.database');

        if ($destination === '' || $destination === ':memory:') {
            $this->error('SQLite restore needs a database file.');

            return self::FAILURE;
        }

        File::copy($source, $destination);
        $this->info('Database restored from '.$source);

        return self::SUCCESS;
    }
}
