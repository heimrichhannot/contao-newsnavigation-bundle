<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\NewsNavigationBundle\EventListener;

use Contao\FrontendTemplate;
use Contao\ModuleNewsReader;
use Contao\System;
use HeimrichHannot\NewsNavigationBundle\NewsFilter\NewsFilter;

class HookListener
{
    /**
     * @param FrontendTemplate $template
     * @param array            $article
     * @param ModuleNewsReader$module
     */
    public function addNewsNavigationLinks(FrontendTemplate $template, array $article, $module)
    {
        $filter = System::getContainer()->get('huh.newsnavigation.newsfilter');
        $filter->filterOnlyPublished();
        if ($module->newsnavigationRespectArchive) {
            $filter->filterByPids($module->news_archives);
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
