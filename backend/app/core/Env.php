<?php

class Env
{
    protected static array $data = [];

    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            self::$data[$name] = $value;
        }
    }

    public static function get(string $key, $default = null)
    {
        return self::$data[$key] ?? getenv($key) ?: $default;
    }
}
