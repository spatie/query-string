<?php

declare(strict_types=1);

namespace Spatie\QueryString;

use Spatie\QueryString\Ast\Ast;
use Spatie\QueryString\Ast\SingleNode;

final class QueryString
{
    /** @var string */
    private $baseUrl;

    /** @var \Spatie\QueryString\Ast\Ast|\Spatie\QueryString\Ast\Node[]|\Spatie\QueryString\Ast\ToggleNode[]|\Spatie\QueryString\Ast\SingleNode[]|\Spatie\QueryString\Ast\MultiNode[] */
    private $ast;

    /** @var string */
    private $filterName = 'filter';

    /** @var string */
    private $sortName = 'sort';

    /** @var array */
    private $defaults = [];

    public static function new(string $uri): QueryString
    {
        return new self($uri);
    }

    public function __construct(string $uri = '')
    {
        $uri = StringHelper::ensureEndsWith($uri, '?');

        [$baseUrl, $query] = explode('?', $uri);

        $this->baseUrl = $baseUrl;

        $this->ast = new Ast($query);
    }

    public function __toString()
    {
        return rtrim("{$this->baseUrl}?{$this->ast}", '?');
    }

    public function withBaseUrl(string $baseUrl): QueryString
    {
        $queryString = clone $this;

        $queryString->baseUrl = $baseUrl;

        return $queryString;
    }

    public function isActive(string $name, $value = null): bool
    {
        if (! isset($this->ast[$name])) {
            return false;
        }

        return $this->ast[$name]->isActive($value);
    }

    public function toggle(string $name, $value = null): QueryString
    {
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
        if (isset($this->defaults[$name]) && $this->defaults[$name] === $value) {
            return $this->disable($name);
        }

        $queryString = clone $this;

        if ($name !== 'page') {
            $queryString = $queryString->resetPage();
        }

        $queryString->ast = $queryString->ast->add($name, $value);

        return $queryString;
    }

    public function disable(string $name, $value = null): QueryString
    {
        $queryString = clone $this;

        if ($name !== 'page') {
            $queryString = $queryString->resetPage();
        }

        $queryString->ast = $queryString->ast->remove($name, $value);

        return $queryString;
    }

    public function default(string $name, $value): QueryString
    {
        $queryString = clone $this;

        $queryString->defaults[$name] = (string) $value;

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

    public function page(int $index): QueryString
    {
        return $this->enable('page', (string) $index);
    }

    public function nextPage(): QueryString
    {
        $index = $this->getCurrentPage() + 1;

        return $this->enable('page', (string) $index);
    }

    public function previousPage(): QueryString
    {
        $index = $this->getCurrentPage() - 1;

        if ($index < 1) {
            $index = 1;
        }

        return $this->enable('page', (string) $index);
    }

    public function resetPage(): QueryString
    {
        return $this->disable('page');
    }

    public function getCurrentPage(): int
    {
        $index = 1;

        if (isset($this->ast['page']) && $this->ast['page'] instanceof SingleNode) {
            $index = $this->ast['page']->value();
        }

        return (int) $index;
    }

    public function isCurrentPage(int $index): bool
    {
        return $this->getCurrentPage() === $index;
    }

    public function resolveFilterName($name): string
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

    public function resolveSortValue($value): string
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
