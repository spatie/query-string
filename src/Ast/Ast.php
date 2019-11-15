<?php

declare(strict_types=1);

namespace Spatie\QueryString\Ast;

use ArrayAccess;
use Spatie\QueryString\StringHelper;
use TypeError;

final class Ast implements ArrayAccess
{
    /** @var \Spatie\QueryString\Ast\Node[] */
    private $nodes = [];

    public function __construct(string $queryString = '')
    {
        $nodeDefinitions = explode('&', ltrim($queryString, '?'));

        foreach ($nodeDefinitions as $nodeDefinition) {
            if ($nodeDefinition === '') {
                continue;
            }

            $node = $this->buildNode($nodeDefinition);

            $this->addNode($node);
        }
    }

    public function __toString(): string
    {
        $nodes = $this->nodes;

        ksort($nodes);

        $parts = array_map('strval', $nodes);

        return implode('&', $parts);
    }

    public function add(string $name, ?string $value): Ast
    {
        $ast = clone $this;

        $definition = ! is_null($value)
            ? "{$name}={$value}"
            : $name;

        $node = $ast->buildNode($definition);

        $ast->addNode($node);

        return $ast;
    }

    public function remove(string $name, ?string $value): Ast
    {
        $ast = clone $this;

        if (! isset($ast[$name])) {
            return $ast;
        }

        $node = $ast[$name];

        if ($value === null) {
            unset($ast[$name]);

            return $ast;
        }

        if ($node instanceof ToggleNode) {
            unset($ast[$name]);

            return $ast;
        }

        if ($node instanceof SingleNode && $node->value() === $value) {
            unset($ast[$name]);

            return $ast;
        }

        if ($node instanceof MultiNode) {
            $ast[$name] = $node->remove($value);

            return $ast;
        }

        return $ast;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->nodes);
    }

    public function offsetGet($offset): Node
    {
        return $this->nodes[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (! $value instanceof Node) {
            throw new TypeError('Value must be instance of '.Node::class);
        }

        $this->nodes[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->nodes[$offset]);
    }

    private function buildNode(string $definition): Node
    {
        if (strpos($definition, '=') === false) {
            return new ToggleNode($definition);
        }

        [$name, $value] = explode('=', $definition);

        if (StringHelper::endsWith($name, '[]')) {
            return new MultiNode($name, (array) $value);
        }

        return new SingleNode($name, $value);
    }

    private function addNode(Node $node): void
    {
        if ($node instanceof MultiNode) {
            $this->addMultiNode($node);

            return;
        }

        $this->nodes[$node->name()] = $node;
    }

    private function addMultiNode(MultiNode $node): void
    {
        if (! isset($this->nodes[$node->name()])) {
            $this->nodes[$node->name()] = $node;

            return;
        }

        /** @var \Spatie\QueryString\Ast\MultiNode $existingNode */
        $existingNode = $this->nodes[$node->name()];

        $this->nodes[$node->name()] = $existingNode->merge($node);
    }
}
