<?php

use Core\Support\Collection;
use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('dd')) {
    /**
     * Dump and Die - Hiển thị dữ liệu giống Laravel và kết thúc chương trình
     *
     * @param mixed ...$vars
     * @return void
     */
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            VarDumper::dump($var);
        }
        die(1);
    }
}
if (!function_exists('toArray')) {
    /**
     * Convert một object thành array bằng Collection
     *
     * @param mixed $object
     * @return array
     */
    function toArray($object): array
    {
        $collect = new Collection($object);
        return $collect->toArray();
    }
}

