<?php

declare(strict_types=1);

namespace Spatie\QueryString\Ast;

final class SingleNode implements Node
{
    /** @var string */
    private $name;

    /** @var string */
    private $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isActive(?string $value): bool
    {
        if ($value === null) {
            return true;
        }

        return $value === $this->value;
    }

    public function __toString(): string
    {
        return "{$this->name}={$this->value}";
    }
}
