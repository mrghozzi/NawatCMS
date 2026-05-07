<?php

declare(strict_types=1);

namespace App\Http\Controllers\Install;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class WelcomeController extends Controller
{
    public function __invoke(): View
    {
        return view('admin::install.index', [
            'checks' => $this->checks(),
            'steps' => [
                'Server checks',
                'Environment setup',
                'Database connection',
                'Administrator account',
            ],
        ]);
    }

    /**
     * @return array<int, array{label: string, value: string, passed: bool}>
     */
    private function checks(): array
    {
        return [
            [
                'label' => 'PHP',
                'value' => PHP_VERSION,
                'passed' => version_compare(PHP_VERSION, '8.2.0', '>='),
            ],
            [
                'label' => 'Storage',
                'value' => storage_path(),
                'passed' => $this->directoryIsWritable(storage_path()),
            ],
            [
                'label' => 'Bootstrap cache',
                'value' => base_path('bootstrap/cache'),
                'passed' => $this->directoryIsWritable(base_path('bootstrap/cache')),
            ],
            [
                'label' => 'Uploads',
                'value' => public_path('nw-content/uploads'),
                'passed' => $this->directoryIsWritable(public_path('nw-content/uploads')),
            ],
        ];
    }

    private function directoryIsWritable(string $path): bool
    {
        return is_dir($path) && is_writable($path);
    }
}
