<?php

declare(strict_types=1);

namespace Spatie\QueryString\Ast;

final class ToggleNode implements Node
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isActive(?string $value): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
