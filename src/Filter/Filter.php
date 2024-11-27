<?php

namespace HeimrichHannot\NewsNavigationBundle\Filter;

use Contao\Model;

class Filter
{
    public readonly string $table;
    public readonly int $id;
    private bool $onlyPublished = true;
    private bool $ignoreFePreview = false;
    private array $pids = [];
    private array $columns = [];
    private array $values = [];
    private array $options = [];

    public function __construct(
        public readonly Model $model
    )
    {
        $this->table = $model::getTable();
        $this->id = $model->id;
    }

    public function isOnlyPublished(): bool
    {
        return $this->onlyPublished;
    }

    public function setOnlyPublished(bool $onlyPublished): Filter
    {
        $this->onlyPublished = $onlyPublished;
        return $this;
    }

    public function isIgnoreFePreview(): bool
    {
        return $this->ignoreFePreview;
    }

    public function setIgnoreFePreview(bool $ignoreFePreview): Filter
    {
        $this->ignoreFePreview = $ignoreFePreview;
        return $this;
    }

    public function getPids(): array
    {
        return $this->pids;
    }

    public function setPids(array $pids): Filter
    {
        $this->pids = $pids;
        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setColumns(array $columns): Filter
    {
        $this->columns = $columns;
        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setValues(array $values): Filter
    {
        $this->values = $values;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): Filter
    {
        $this->options = $options;
        return $this;
    }
}