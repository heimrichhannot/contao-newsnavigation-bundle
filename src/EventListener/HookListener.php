<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\NewsNavigationBundle\EventListener;


use Contao\Controller;
use Contao\FrontendTemplate;
use Contao\ModuleNewsReader;
use Contao\System;
use HeimrichHannot\Haste\Util\Url;
use HeimrichHannot\NewsNavigationBundle\Model\NewsModel;

class HookListener
{

    /**
     * @param FrontendTemplate $template
     * @param array $article
     * @param ModuleNewsReader$module
     */
    public function addNewsNavigationLinks ($template, $article, $module)
    {
        $nextArticle = NewsModel::findNextPublishedByReleaseDate($article['time']);
        $previousArticle = NewsModel::findPreviousPublishedByReleaseDate($article['time']);
        $template->nextArticle = $nextArticle->id;
        $template->previousArticle = $previousArticle->id;

        $template->nextArticleLabel = System::getContainer()->get('translator')->trans('huh.newsnavigation.article.next');
        $template->previousArticleLabel = System::getContainer()->get('translator')->trans('huh.newsnavigation.article.previous');
    }

}