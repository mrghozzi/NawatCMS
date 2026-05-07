<?php

declare(strict_types=1);

namespace App\Services;

final readonly class EnvService
{
    public function __construct(
        private string $envPath
    ) {}

    /**
     * Update or add a key-value pair in the .env file.
     * 
     * @param array<string, string> $data
     * @return bool
     */
    public function update(array $data): bool
    {
        if (!file_exists($this->envPath)) {
            return false;
        }

        $content = file_get_contents($this->envPath);

        if ($content === false) {
            return false;
        }

        foreach ($data as $key => $value) {
            $key = strtoupper($key);
            
            // Handle values with spaces
            if (str_contains($value, ' ') && !str_starts_with($value, '"')) {
                $value = '"' . $value . '"';
            }

            $pattern = "/^{$key}=(.*)$/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $replacement, $content);
            } else {
                $content .= "\n{$replacement}";
            }
        }

        return file_put_to_file($this->envPath, trim($content) . "\n") !== false;
    }

    /**
     * Get a value from the .env file.
     */
    public function get(string $key, string $default = ''): string
    {
        if (!file_exists($this->envPath)) {
            return $default;
        }

        $content = file_get_contents($this->envPath);
        
        if ($content === false) {
            return $default;
        }

        $pattern = "/^{$key}=(.*)$/m";
        
        if (preg_match($pattern, $content, $matches)) {
            return trim($matches[1], '"\' ');
        }

        return $default;
    }
}

/**
 * Helper to ensure directory existence before writing file.
 */
if (!function_exists('file_put_to_file')) {
    function file_put_to_file(string $path, string $content): bool|int
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return file_put_contents($path, $content);
    }
}
