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


use Contao\FrontendTemplate;
use Contao\ModuleNewsReader;
use Contao\System;
use HeimrichHannot\NewsNavigationBundle\NewsFilter\FilterFactory;
use HeimrichHannot\NewsNavigationBundle\NewsFilter\NewsFilter;

class HookListener
{

    /**
     * @param FrontendTemplate $template
     * @param array $article
     * @param ModuleNewsReader$module
     */
    public function addNewsNavigationLinks ($template, $article, $module)
    {

        $filter = System::getContainer()->get('huh.newsnavigation.newsfilter');
        $filter->filterOnlyPublished();
        if ($module->newsnavigationRespectArchive)
        {
            $filter->filterByArchive($article['pid']);
        }

        $olderArticle = $filter->createCopy()
            ->filterOnlyOlderArticles($article['time'])
            ->getSingleNewsArticle();
        $newerArticle = $filter->createCopy()
            ->filterOnlyNewerArticles($article['time'])
            ->getSingleNewsArticle();


        $template->nextArticle = $newerArticle->id;
        $template->previousArticle = $olderArticle->id;

        $template->nextArticleLabel = System::getContainer()->get('translator')->trans('huh.newsnavigation.article.next');
        $template->previousArticleLabel = System::getContainer()->get('translator')->trans('huh.newsnavigation.article.previous');
    }

}