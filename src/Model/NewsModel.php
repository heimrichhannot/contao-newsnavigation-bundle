<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\NewsNavigationBundle\Model;


class NewsModel extends \Contao\NewsModel
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_news';

    /**
     * Return the next (newer) article
     *
     * @param $time
     * @param array $columns
     * @param array $options
     * @return \Contao\NewsModel|null
     */
    public static function findNextPublishedByReleaseDate ($time, $columns = [], $options = [])
    {
        $t = static::$strTable;
        $values = [];
        $columns[] = "$t.time > ?";
        $values[] = $time;
        if (empty($options['order']))
        {
            $options['order'] = "$t.time ASC";
        }
        else {
            $options['order'] .= ", $t.time ASC";
        }
        return static::findOnePublished($columns, $values, $options);
    }

    /**
     * Return the previous (older) article
     *
     * @param $time
     * @param array $columns
     * @param array $options
     * @return \Contao\NewsModel|null
     */
    public static function findPreviousPublishedByReleaseDate ($time, $columns = [], $options = [])
    {
        $t = static::$strTable;
        $values = [];
        $columns[] = "$t.time < ?";
        $values[] = $time;
        if (empty($options['order']))
        {
            $options['order'] = "$t.time DESC";
        }
        else {
            $options['order'] .= ", $t.time DESC";
        }
        return static::findOnePublished($columns, $values, $options);
    }

    public static function findOnePublished ($columns = [], $values = [], $options = [])
    {
        $t = static::$strTable;
        if (isset($arrOptions['ignoreFePreview']) || !BE_USER_LOGGED_IN)
        {
            $time = \Date::floorToMinute();
            $columns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }
        return static::findOneBy($columns, $values, $options);
    }

}