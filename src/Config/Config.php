<?php

namespace PandaCore\Config;
class Config
{
    /**
     * Lấy giá trị từ biến môi trường
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
}
