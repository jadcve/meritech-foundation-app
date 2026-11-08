<?php

namespace Tests\Feature\Operations;

use Illuminate\Support\Facades\File;
use PDO;
use Tests\TestCase;

class BackupTest extends TestCase
{
    public function test_sqlite_backup_and_restore_round_trip(): void
    {
        $directory = storage_path('framework/testing-backups');
        File::ensureDirectoryExists($directory);
        $source = $directory.'/source.sqlite';
        $backup = $directory.'/backup.sqlite';
        File::delete([$source, $backup]);

        $pdo = new PDO('sqlite:'.$source);
        $pdo->exec('create table marks (id integer primary key, label text)');
        $pdo->exec("insert into marks (label) values ('antes')");

        config([
            'database.default' => 'backup_sqlite',
            'database.connections.backup_sqlite' => [
                'driver' => 'sqlite',
                'database' => $source,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        $this->artisan('foundation:backup', ['path' => $backup])->assertSuccessful();
        $this->assertFileExists($backup);

        $pdo->exec("update marks set label = 'despues'");
        $this->artisan('foundation:restore', ['path' => $backup, '--force' => true])->assertSuccessful();

        $restored = (new PDO('sqlite:'.$source))->query('select label from marks')->fetchColumn();
        $this->assertSame('antes', $restored);

        $this->artisan('foundation:restore', ['path' => $backup])->assertFailed();
        File::delete([$source, $backup]);
    }
}
