<?php
declare(strict_types=1);

namespace AL\PhpWndb\Cache;

interface CacheInterface
{
    /**
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);
    
    /**
     * @param mixed $value
     */
    public function set(string $key, $value): void;
}
