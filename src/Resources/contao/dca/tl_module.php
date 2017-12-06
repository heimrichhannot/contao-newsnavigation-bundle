<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dca = &$GLOBALS['TL_DCA']['tl_module'];

$dca['palettes']['newsreader'] = str_replace('news_archives', 'news_archives,newsnavigationRespectArchive', $dca['palettes']['newsreader']);

$translator = System::getContainer()->get('translator');

$fields = [
    'newsnavigationRespectArchive'              => [
        'label'     =>[
            $translator->trans('huh.newsnavigation.tl_module.newsnavigationRespectArchive.0'),
            $translator->trans('huh.newsnavigation.tl_module.newsnavigationRespectArchive.1'),
        ],
        'inputType' => 'checkbox',
        'exclude'   => true,
        'sql'       => "char(1) NOT NULL default ''"
    ],
];

$dca['fields'] = array_merge($dca['fields'], $fields);
