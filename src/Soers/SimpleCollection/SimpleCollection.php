<?php
declare(strict_types = 1);

namespace Soers\SimpleCollection;

use Countable;

interface SimpleCollection extends Countable, \IteratorAggregate, \ArrayAccess
{

    /**
     * @param mixed $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param mixed $item
     */
    public function add($item): void;

    /**
     * @param mixed $key
     * @param mixed $item
     */
    public function set($key, $item): void;

    /**
     * @param mixed $item
     * @param bool $strict
     * @return mixed
     */
    public function find($item, bool $strict = false);
    
    public function map(\Closure $f): SimpleCollection;

    public function filter(\Closure $f): SimpleCollection;

    public function filterByClass(string $className): SimpleCollection;
    
    public function intersect(SimpleCollection $collection): SimpleCollection;

    public function diff(SimpleCollection $collection): SimpleCollection;
    
    public function unique(): SimpleCollection;

    public function merge(SimpleCollection... $collections): SimpleCollection;
    
    public function count(): int;
    
    public function getIterator(): \Traversable;

    public function toArray();

}
