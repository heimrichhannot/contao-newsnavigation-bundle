<?php

namespace HeimrichHannot\NewsNavigationBundle\Filter;

use Contao\CoreBundle\Security\Authentication\Token\TokenChecker;
use Contao\Date;
use Contao\Model;

class Finder
{
    public function __construct(
        private readonly TokenChecker $tokenChecker,
    ) {
    }

    public function findPreviousElement(Filter $filter): ?Model
    {
        return $this->find($filter, [$this, 'applyOlderFilter']);
    }

    public function findNextElement(Filter $filter): ?Model
    {
        return $this->find($filter, [$this, 'applyNewerFilter']);
    }

    private function find(Filter $filter, callable $positionCallback): ?Model
    {
        $columns = [];
        $values = [];
        $options = [];

        $this->applyPublishedFilter($filter, $columns);
        $this->applyPidFilter($filter, $columns);

        $positionCallback($filter->model, $columns, $values, $options);

        $this->applyCustomFilter($filter, $columns, $values, $options);

        return $filter->model::findOneBy($columns, $values, $options);
    }

    private function isPreviewMode(Filter $filter): bool
    {
        if ($filter->isIgnoreFePreview()) {
            return false;
        }

        return $this->tokenChecker->isPreviewMode();
    }

    private function applyPublishedFilter(Filter $filter, array &$columns): void
    {
        $t = $filter->table;
        if ($filter->isOnlyPublished()) {
            if (!$this->isPreviewMode($filter)) {
                $time = Date::floorToMinute();
                $columns[] = "$t.published='1' AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
            }
        }
    }

    private function applyPidFilter(Filter $filter, array &$columns): void
    {
        $t = $filter->table;
        $columns[] = "$t.pid IN(" . implode(',', array_map('intval', $filter->getPids())) . ')';
    }

    private function applyCustomFilter(Filter $filter, array &$columns, array &$values, array &$options): void
    {
        $columns = array_merge($columns, $filter->getColumns());
        $values = array_merge($values, $filter->getValues());
        $options = array_merge($options, $filter->getOptions());
    }

    private function applyOlderFilter(Model $model, array &$columns, array &$values, array &$options): void
    {
        $t = $model::getTable();
        $time = $model->time;
        $columns[] = "$t.time < ?";
        $values[] = $time;
        if (!$options['order']) {
            $options['order'] = "$t.time DESC";
        } else {
            $options['order'] .= ", $t.time DESC";
        }
    }

    private function applyNewerFilter(Model $model, array &$columns, array &$values, array &$options): void
    {
        $t = $model::getTable();
        $time = $model->time;
        $columns[] = "$t.time > ?";
        $values[] = $time;
        if (!$options['order']) {
            $options['order'] = "$t.time ASC";
        } else {
            $options['order'] .= ", $t.time ASC";
        }
    }
}