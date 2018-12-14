<?php

declare(strict_types=1);

namespace Spatie\QueryString\Ast;

interface Node
{
    public function name(): string;

    public function __toString(): string;

    public function isActive(?string $value): bool;
}
