<?php

declare(strict_types=1);

namespace Spatie\QueryString\Ast;

final class MultiNode implements Node
{
    /** @var string */
    private $name;

    /** @var array */
    private $values;

    public function __construct(string $name, array $values)
    {
        $this->name = $name;

        foreach ($values as $value) {
            $this->values[$value] = $value;
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function values(): array
    {
        return $this->values;
    }

    public function merge(MultiNode $otherNode): MultiNode
    {
        $node = clone $this;

        foreach ($otherNode->values as $value) {
            $node->values[$value] = $value;
        }

        return $node;
    }

    public function remove(string $value): MultiNode
    {
        $node = clone $this;

        unset($node->values[$value]);

        return $node;
    }

    public function __toString(): string
    {
        $parts = [];

        foreach ($this->values as $value) {
            $parts[] = "{$this->name}={$value}";
        }

        return implode('&', $parts);
    }

    public function isActive(?string $value): bool
    {
        if ($value === null) {
            return true;
        }

        return isset($this->values[$value]);
    }
}
