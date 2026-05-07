<?php

declare(strict_types=1);

$configPath = __DIR__ . '/config.json';
$configJson = is_file($configPath) ? file_get_contents($configPath) : false;
$config = is_string($configJson) ? json_decode($configJson, true) : [];

if (!is_array($config)) {
    $config = [];
}

function cfg(array $config, array $path, $default = null)
{
    $value = $config;

    foreach ($path as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
