<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\NewsNavigationBundle\NewsFilter;

use Contao\NewsModel;

class NewsFilter
{
    /**
     * @var NewsModel
     */
    private $model;
    private $columns = [];
    private $values = [];
    private $options = [];

    /**
     * NewsArticle constructor.
     */
    public function __construct()
    {
        $this->setModel(new NewsModel());
    }

    /**
     * Filter only published articles
     *
     * @return $this
     */
    public function filterOnlyPublished()
    {
        $t = $this->table;
        if (isset($this->options['ignoreFePreview']) || !BE_USER_LOGGED_IN)
        {
            $time            = \Date::floorToMinute();
            $this->columns[] = "($t.start = '' OR $t.start <= ?)";
            $this->values[]  = $time;
            $this->columns[] = "($t.stop = '' OR $t.stop > ?)";
            $this->values[]  = $time + 60;
            $this->columns[] = "$t.published = ?";
            $this->values[]  = 1;
        }

        return $this;
    }

    /**
     * Filter by a single archive
     *
     * @param integer $pid
     * @return $this
     */
    public function filterByArchive($pid)
    {
        $t = $this->table;
        if ($pid && is_numeric($pid))
        {
            $this->columns[] = "pid = ?";
            $this->values[]  = $pid;
        }
        return $this;
    }

    /**
     * Set the filter direction to only older articles
     *
     * @param int $time Timestamp
     * @return $this
     */
    public function filterOnlyOlderArticles($time)
    {
        $t         = $this->table;
        $columns[] = "$t.time < ?";
        $values[]  = $time;
        if (!$this->options['order'])
        {
            $this->options['order'] = "$t.time DESC";
        } else
        {
            $this->options['order'] .= ", $t.time DESC";
        }
        return $this;
    }

    /**
     * Set the filter article to only news articles
     *
     * @param int $time Timestamp
     * @return $this
     */
    public function filterOnlyNewerArticles($time)
    {
        $t         = $this->table;
        $columns[] = "$t.time > ?";
        $values[]  = $time;
        if (!$this->options['order'])
        {
            $this->options['order'] = "$t.time ASC";
        } else
        {
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
     * @return $this
     */
    public function addFilter(string $column, string $value)
    {
        $this->columns[] = $column;
        $this->values[]  = $value;
        return $this;
    }

    /**
     * Add additions options to the filter. If option is already set, it will be appended.
     *
     * @param string $key
     * @param string $value
     * @param bool $forceOverwrite If true, option will be overwritten, if already exist.
     * @return $this
     */
    public function setOption(string $key, string $value, bool $forceOverwrite = false)
    {
        if (!$this->options[$key] || $forceOverwrite === true)
        {
            $this->options[$key] = $value;
        } else
        {
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
     * Returns a copy of this object
     *
     * @return NewsFilter
     */
    public function createCopy()
    {
        $filter = new NewsFilter();
        $filter->setColumns($this->getColumns());
        $filter->setValues($this->getValues());
        $filter->setOptions($this->getOptions());
        return $filter;
    }

}