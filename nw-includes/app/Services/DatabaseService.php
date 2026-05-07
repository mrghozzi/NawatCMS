<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PDO;

final class DatabaseService
{
    /**
     * Test a database connection with the given credentials.
     * 
     * @param array{driver: string, host?: string, port?: string, database: string, username?: string, password?: string} $config
     * @return bool
     * @throws Exception
     */
    public function testConnection(array $config): bool
    {
        $driver = $config['driver'];
        
        if ($driver === 'sqlite') {
            return $this->testSqlite($config['database']);
        }

        $dsn = "{$driver}:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        
        try {
            new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5,
            ]);
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function testSqlite(string $database): bool
    {
        if ($database === ':memory:') {
            return true;
        }

        // Ensure path is absolute if it's not
        $isAbsolute = str_starts_with($database, '/') || 
                      str_starts_with($database, '\\') || 
                      (strlen($database) > 1 && $database[1] === ':');

        if (!$isAbsolute) {
            $database = database_path($database);
        }

        $directory = dirname($database);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (!file_exists($database)) {
            touch($database);
        }

        return is_writable($database);
    }

    /**
     * Run the application migrations.
     * 
     * @return array{success: bool, output: string}
     */
    public function runMigrations(): array
    {
        try {
            $exitCode = Artisan::call('migrate', [
                '--force' => true,
            ]);
            
            return [
                'success' => $exitCode === 0,
                'output' => Artisan::output(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'output' => $e->getMessage(),
            ];
        }
    }
}
