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

    /** @var string */
    private $filterName = 'filter';

    /** @var string */
    private $sortName = 'sort';

    public static function new(string $uri): QueryString
    {
        return new self($uri);
    }

    public function __construct(string $uri = '')
    {
        if (strpos($uri, '?') === false) {
            $uri = "{$uri}?";
        }

        [$baseUrl, $query] = explode('?', $uri);

        $this->baseUrl = $baseUrl;

        $this->ast = new Ast($query);
    }

    public function __toString()
    {
        return "{$this->baseUrl}?{$this->ast}";
    }

    public function withBaseUrl(string $baseUrl): QueryString
    {
        $queryString = clone $this;

        $queryString->baseUrl = $baseUrl;

        return $queryString;
    }

    public function isActive(
        string $name,
        $value = null
    ): bool {
        if (! isset($this->ast[$name])) {
            return false;
        }

        return $this->ast[$name]->isActive($value);
    }

    public function toggle(
        string $name,
        $value = null
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

    public function enable(string $name, $value = null): QueryString
    {
        $queryString = clone $this;

        $queryString->ast = $this->ast->add($name, $value);

        return $queryString;
    }

    public function disable(string $name, $value = null): QueryString
    {
        $queryString = clone $this;

        $queryString->ast = $this->ast->remove($name, $value);

        return $queryString;
    }

    public function filter(string $name, $value = null): QueryString
    {
        $filterName = $this->resolveFilterName($name);

        return $this->toggle($filterName, $value);
    }

    public function sort($value): QueryString
    {
        $value = $this->resolveSortValue($value);

        return $this->toggle($this->sortName, $value);
    }

    private function resolveFilterName($name): string
    {
        $isMultiple = StringHelper::endsWith($name, '[]');

        if ($isMultiple) {
            $name = StringHelper::replaceLast('[]', '', $name);
        }

        if (strpos($name, "{$this->filterName}[") !== 0) {
            $name = "{$this->filterName}[{$name}]";
        }

        if ($isMultiple) {
            $name .= '[]';
        }

        return $name;
    }

    private function resolveSortValue($value): string
    {
        if (! $this->isActive($this->sortName, $value)) {
            return $value;
        }

        if (StringHelper::startsWith($value, '-')) {
            return substr($value, 1);
        }

        return "-{$value}";
    }
}
