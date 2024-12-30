<?php

use PandaCore\Core\Support\Collection;
use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('dd')) {
    /**
     * Dump and Die - Display data similar to Laravel and end the program.
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
     * Convert an object to an array using Collection
     *
     * @param mixed $object
     * @return array
     */
    function toArray(mixed $object): array
    {
        $collect = new Collection($object);
        return $collect->toArray();
    }
}

