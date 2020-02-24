<?php declare(strict_types=1);

namespace AL\PhpWndb\Cache;

class MemoryCache implements CacheInterface
{
    /** It's prepanded to every key to avoid numeric keys */
    private const KEY_PREFIX = '_';


    /** @var array<string,mixed> */
    private $items = [];

    /** @var int */
    private $maxItemsCount;


    public function __construct(int $maxItemsCount)
    {
        $this->maxItemsCount = $maxItemsCount;
    }


    /**
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return array_key_exists(self::KEY_PREFIX . $key, $this->items) ? $this->items[self::KEY_PREFIX . $key] : $default;
    }
    
    /**
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $this->items[self::KEY_PREFIX . $key] = $value;
        if (count($this->items) > $this->maxItemsCount) {
            array_shift($this->items);
        }
    }
}