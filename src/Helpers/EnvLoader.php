<?php
namespace PandaCore\Helpers;

use Exception;

class EnvLoader
{
    /**
     * Load environment variables từ file .env
     * @param string $filePath
     * @throws Exception
     */
    public static function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("File .env doesn't exist: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (!array_key_exists($key, $_ENV) && !array_key_exists($key, getenv())) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }
}
