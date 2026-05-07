<?php

declare(strict_types=1);

namespace App\Http\Controllers\Install;

use App\Services\DatabaseService;
use App\Services\EnvService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

final class DatabaseController extends Controller
{
    public function __construct(
        private DatabaseService $databaseService,
        private EnvService $envService
    ) {}

    public function index(): View
    {
        return view('admin::install.database', [
            'driver' => $this->envService->get('DB_CONNECTION', 'mysql'),
            'host' => $this->envService->get('DB_HOST', '127.0.0.1'),
            'port' => $this->envService->get('DB_PORT', '3306'),
            'database' => $this->envService->get('DB_DATABASE', ''),
            'username' => $this->envService->get('DB_USERNAME', 'root'),
            'password' => $this->envService->get('DB_PASSWORD', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'driver' => ['required', 'in:mysql,sqlite,pgsql'],
            'host' => ['required_if:driver,mysql,pgsql', 'nullable', 'string'],
            'port' => ['required_if:driver,mysql,pgsql', 'nullable', 'string'],
            'database' => ['required', 'string'],
            'username' => ['required_if:driver,mysql,pgsql', 'nullable', 'string'],
            'password' => ['nullable', 'string'],
        ]);

        try {
            // Test connection
            $this->databaseService->testConnection($validated);

            // Update .env
            $envData = [
                'DB_CONNECTION' => $validated['driver'],
                'SESSION_DRIVER' => 'file', // Use file session during installation to avoid 500 errors
            ];

            if ($validated['driver'] === 'sqlite') {
                $dbPath = $validated['database'];
                if (!str_starts_with($dbPath, DIRECTORY_SEPARATOR) && !str_contains($dbPath, ':')) {
                    $dbPath = database_path($dbPath);
                }
                $envData['DB_DATABASE'] = $dbPath;
                $envData['DB_HOST'] = '#';
                $envData['DB_PORT'] = '#';
                $envData['DB_USERNAME'] = '#';
                $envData['DB_PASSWORD'] = '#';
                
                // Ensure file exists
                $this->databaseService->testConnection($validated);
            } else {
                $envData['DB_DATABASE'] = $validated['database'];
                $envData['DB_HOST'] = $validated['host'];
                $envData['DB_PORT'] = $validated['port'];
                $envData['DB_USERNAME'] = $validated['username'];
                $envData['DB_PASSWORD'] = $validated['password'] ?? '';
            }

            $this->envService->update($envData);

            // Dynamically set config for migration
            $this->setRuntimeConfig($validated);

            // Run migrations
            $migrationResult = $this->databaseService->runMigrations();

            if (!$migrationResult['success']) {
                return back()->withInput()->withErrors(['error' => 'Migration failed: ' . $migrationResult['output']]);
            }

            return redirect()->route('install.admin');

        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Connection failed: ' . $e->getMessage()]);
        }
    }

    private function setRuntimeConfig(array $config): void
    {
        $driver = $config['driver'];
        $connectionName = "db_test";

        Config::set("database.connections.{$connectionName}", [
            'driver' => $driver,
            'host' => $config['host'] ?? null,
            'port' => $config['port'] ?? null,
            'database' => $driver === 'sqlite' ? ($config['database'] === ':memory:' ? ':memory:' : database_path($config['database'])) : $config['database'],
            'username' => $config['username'] ?? null,
            'password' => $config['password'] ?? null,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ]);

        Config::set('database.default', $connectionName);
        DB::purge($connectionName);
    }
}
