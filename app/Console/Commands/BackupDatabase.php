<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'foundation:backup {path : Destination file for the database copy}';

    protected $description = 'Copy the application database to a local file.';

    public function handle(): int
    {
        $destination = $this->argument('path');
        File::ensureDirectoryExists(dirname($destination));

        return match (config('database.connections.'.config('database.default').'.driver')) {
            'sqlite' => $this->backupSqlite($destination),
            'mysql', 'mariadb' => $this->runDump('mysqldump', $destination, restore: false),
            'pgsql' => $this->runDump('pg_dump', $destination, restore: false),
            default => $this->unsupported(),
        };
    }

    private function backupSqlite(string $destination): int
    {
        $source = (string) config('database.connections.'.config('database.default').'.database');

        if ($source === '' || $source === ':memory:' || ! is_file($source)) {
            $this->error('SQLite backup needs a database file. In-memory databases cannot be copied.');

            return self::FAILURE;
        }

        File::copy($source, $destination);
        $this->info('Backup written to '.$destination);

        return self::SUCCESS;
    }

    private function runDump(string $binary, string $destination, bool $restore): int
    {
        unset($restore);
        $resolved = $this->binary($binary);

        if ($resolved === null) {
            $this->error($binary.' is not installed. Install the client tools or use the sqlite driver.');

            return self::FAILURE;
        }

        $connection = config('database.connections.'.config('database.default'));
        $process = new Process(
            $this->dumpCommand($binary, $resolved, $connection, $destination),
            null,
            $this->dumpEnvironment($binary, $connection),
        );
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error(trim($process->getErrorOutput()) ?: 'Database backup failed.');

            return self::FAILURE;
        }

        $this->info('Backup written to '.$destination);

        return self::SUCCESS;
    }

    /**
     * @param  array<string, mixed>  $connection
     * @return array<int, string>
     */
    private function dumpCommand(string $binary, string $resolved, array $connection, string $destination): array
    {
        if ($binary === 'pg_dump') {
            return [$resolved, '--file='.$destination, '--dbname='.$this->pgsqlDsn($connection)];
        }

        return [
            $resolved,
            '--host='.($connection['host'] ?? '127.0.0.1'),
            '--port='.(string) ($connection['port'] ?? 3306),
            '--user='.(string) ($connection['username'] ?? ''),
            '--result-file='.$destination,
            (string) ($connection['database'] ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>  $connection
     * @return array<string, string>
     */
    private function dumpEnvironment(string $binary, array $connection): array
    {
        $password = (string) ($connection['password'] ?? '');

        if ($binary === 'pg_dump') {
            return ['PGPASSWORD' => $password];
        }

        return ['MYSQL_PWD' => $password];
    }

    /**
     * @param  array<string, mixed>  $connection
     */
    private function pgsqlDsn(array $connection): string
    {
        return sprintf(
            'postgresql://%s@%s:%s/%s',
            rawurlencode((string) ($connection['username'] ?? '')),
            $connection['host'] ?? '127.0.0.1',
            $connection['port'] ?? 5432,
            $connection['database'] ?? '',
        );
    }

    private function binary(string $name): ?string
    {
        $path = trim((string) shell_exec('command -v '.escapeshellarg($name)));

        return $path === '' ? null : $path;
    }

    private function unsupported(): int
    {
        $this->error('This database driver has no local backup command.');

        return self::FAILURE;
    }
}
