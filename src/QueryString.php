<?php

declare(strict_types=1);

namespace Spatie\QueryString;

use Spatie\QueryString\Ast\Ast;

final class QueryString
{
    /** @var string */
    private $baseUrl;

    /** @var \Spatie\QueryString\Ast\Ast */
    private $ast;

    public static function new(string $uri): QueryString
    {
        return new self($uri);
    }

    public function __construct(string $uri= '')
    {
        if (strpos($uri, '?') === false) {
            $uri = "{$uri}?";
        }

        [$baseUrl, $query] = explode('?', $uri);

        $this->baseUrl = $baseUrl;

        $this->ast = new Ast($query);
    }

    public function withBaseUrl(string $baseUrl): QueryString
    {
        $queryString = clone $this;

        $queryString->baseUrl = $baseUrl;

        return $queryString;
    }

    public function isActive(
        string $name,
        ?string $value = null
    ): bool {
        if (! isset($this->ast[$name])) {
            return false;
        }

        return $this->ast[$name]->isActive($value);
    }

    public function filter(
        string $name,
        ?string $value = null
    ): QueryString {
        return $this->isActive($name, $value)
            ? $this->disable($name, $value)
            : $this->enable($name, $value);
    }

    public function clear(string $name): QueryString
    {
        $queryString = clone $this;

        $ast = clone $this->ast;

        unset($ast[$name]);

        $queryString->ast = $ast;

        return $queryString;
    }

    public function enable(string $name, ?string $value): QueryString
    {
        $queryString = clone $this;

        $queryString->ast = $this->ast->add($name, $value);

        return $queryString;
    }

    public function disable(string $name, ?string $value): QueryString
    {
        $queryString = clone $this;

        $queryString->ast = $this->ast->remove($name, $value);

        return $queryString;
    }

    public function __toString()
    {
        return "{$this->baseUrl}?{$this->ast}";
    }
}
