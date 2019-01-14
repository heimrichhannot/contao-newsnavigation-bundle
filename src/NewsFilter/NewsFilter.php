<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\NewsNavigationBundle\NewsFilter;

use Contao\NewsModel;

class NewsFilter
{
    /**
     * @var NewsModel
     */
    private $model;
    private $table;
    private $columns = [];
    private $values = [];
    private $options = [];

    /**
     * NewsArticle constructor.
     *
     * @param NewsModel|null $model
     */
    public function __construct($model = null)
    {
        if (null === $model) {
            $model = new NewsModel();
        }
        $this->setModel($model);
    }

    /**
     * Filter only published articles.
     *
     * @return $this
     */
    public function filterOnlyPublished()
    {
        $t = $this->table;
        if (isset($this->options['ignoreFePreview']) || !BE_USER_LOGGED_IN) {
            $time = \Date::floorToMinute();
            $this->columns[] = "($t.start = '' OR $t.start <= ?)";
            $this->values[] = $time;
            $this->columns[] = "($t.stop = '' OR $t.stop > ?)";
            $this->values[] = $time + 60;
            $this->columns[] = "$t.published = ?";
            $this->values[] = 1;
        }

        return $this;
    }

    /**
     * Filter by a single archive.
     *
     * @param array $pids
     *
     * @return $this
     */
    public function filterByPids(array $pids)
    {
        $t = $this->table;
        $this->columns[] = "$t.pid IN(".implode(',', array_map('intval', $pids)).')';

        return $this;
    }

    /**
     * Set the filter direction to only older articles.
     *
     * @param int $time Timestamp
     *
     * @return $this
     */
    public function filterOnlyOlderArticles($time)
    {
        $t = $this->table;
        $this->columns[] = "$t.time < ?";
        $this->values[] = $time;
        if (!$this->options['order']) {
            $this->options['order'] = "$t.time DESC";
        } else {
            $this->options['order'] .= ", $t.time DESC";
        }

        return $this;
    }

    /**
     * Set the filter article to only news articles.
     *
     * @param int $time Timestamp
     *
     * @return $this
     */
    public function filterOnlyNewerArticles($time)
    {
        $t = $this->table;
        $this->columns[] = "$t.time > ?";
        $this->values[] = $time;
        if (!$this->options['order']) {
            $this->options['order'] = "$t.time ASC";
        } else {
            $this->options['order'] .= ", $t.time ASC";
        }

        return $this;
    }

    /**
     * @return NewsModel
     */
    public function getModel(): NewsModel
    {
        return $this->model;
    }

    /**
     * @param NewsModel $model
     *
     * @return $this
     */
    public function setModel(NewsModel $model)
    {
        $this->model = $model;
        $this->table = $this->model->getTable();

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     *
     * @return $this
     */
    public function addFilter(string $column, string $value = '')
    {
        $this->columns[] = $column;
        if (!empty($value)) {
            $this->values[] = $value;
        }

        return $this;
    }

    /**
     * Add additions options to the filter.
     * Some options will be appended instead of overwritten by default ("order"),.
     *
     * @param string $key
     * @param string $value
     * @param bool   $forceOverwrite if true, option will always be overwritten
     *
     * @return $this
     */
    public function setOption(string $key, string $value, bool $forceOverwrite = false)
    {
        $append = ['order'];
        if (!$this->options[$key] || true === $forceOverwrite || !in_array($key, $append, true)) {
            $this->options[$key] = $value;
        } else {
            $this->options[$key] .= ", $value";
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $columns
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Return a single news article based on the given filters or null, if no article found.
     *
     * @return NewsModel|null
     */
    public function getSingleNewsArticle()
    {
        return $this->model->findOneBy($this->columns, $this->values, $this->options);
    }

    /**
     * Returns a copy of this object.
     *
     * @return NewsFilter
     */
    public function createCopy()
    {
        $filter = new self();
        $filter->setColumns($this->getColumns());
        $filter->setValues($this->getValues());
        $filter->setOptions($this->getOptions());

        return $filter;
    }
}
