<?php declare(strict_types = 1);

namespace Soers\SimpleCollection;

class SimpleArrayCollection implements SimpleCollection
{

    /**
     * @var array
     */
    private $elements;

    /**
     * @var string
     */
    private $classRestriction;

    public function __construct(array $elements = [], string $classRestriction = null)
    {
        $this->classRestriction = $classRestriction;
        $this->checkClassRestriction($elements);

        $this->elements = $elements;
    }

    /**
     * @param mixed $key
     * @return mixed
     */
    public function get($key)
    {
       return isset($this->elements[$key]) ? $this->elements[$key] : null;
    }

    /**
     * @param mixed $item
     */
    public function add($item): void
    {
        $this->checkClassRestriction([$item]);
        $this->elements[] = $item;
    }

    /**
     * @param mixed $key
     * @param mixed $item
     */
    public function set($key, $item): void
    {
        $this->checkClassRestriction([$item]);
        $this->elements[$key] = $item;
    }

    /**
     * @param mixed $item
     * @param bool $strict
     * @return mixed
     */
    public function find($item, bool $strict = false)
    {
        $this->checkClassRestriction([$item]);
        return array_search($item, $this->elements, $strict);
    }

    public function map(\Closure $f): SimpleCollection
    {
        return new self(array_map($f, $this->elements), $this->classRestriction);
    }

    public function filter(\Closure $f): SimpleCollection
    {
        return new self(array_filter($this->elements, $f), $this->classRestriction);
    }

    public function filterByClass(string $className): SimpleCollection
    {
        return new self(array_filter($this->elements, function($element) use ($className) {
           return $element instanceof $className;
        }));
    }

    public function intersect(SimpleCollection $collection): SimpleCollection
    {
        return new self(array_intersect($this->elements, $collection->toArray()), $this->classRestriction);
    }

    public function diff(SimpleCollection $collection): SimpleCollection
    {
       return new self(array_diff($this->elements, $collection->toArray()), $this->classRestriction);
    }

    public function unique(): SimpleCollection
    {
        return new self(array_unique($this->elements), $this->classRestriction);
    }

    public function merge(SimpleCollection $collection): SimpleCollection
    {

        return new self(array_merge($this->elements, $collection->toArray()));
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * @param array $items
     */
    private function checkClassRestriction(array $items)
    {
        if (empty($this->classRestriction)) {
            return;
        }

        foreach ($items as $item) {
            if (!$item instanceof $this->classRestriction) {
                throw new \InvalidArgumentException();
            }
        }
    }

}