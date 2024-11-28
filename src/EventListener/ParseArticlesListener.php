<?php

namespace HeimrichHannot\NewsNavigationBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\FrontendTemplate;
use Contao\Module;
use Contao\ModuleNewsReader;
use Contao\NewsModel;
use Contao\StringUtil;
use Contao\Template;
use HeimrichHannot\NewsNavigationBundle\Event\NewsNavigationFilterEvent;
use HeimrichHannot\NewsNavigationBundle\Filter\Filter;
use HeimrichHannot\NewsNavigationBundle\Filter\Finder;
use HeimrichHannot\NewsNavigationBundle\News\Article;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsHook("parseArticles")]
class ParseArticlesListener
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly Finder $finder,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(FrontendTemplate $template, array $article, Module $module): void
    {
        if (!$module instanceof ModuleNewsReader) {
            return;
        }

        $model = NewsModel::findByPk($article['id']);
        $filter = new Filter($model);
        $filter->setOnlyPublished(true);
        $filter->setPids(StringUtil::deserialize($module->news_archives));

        $event = $this->eventDispatcher->dispatch(new NewsNavigationFilterEvent($filter, $module->getModel()));

        if (!empty(StringUtil::deserialize($event->moduleModel->categories, true))) {
            $filter->setColumns(array_merge($filter->getColumns(), ['tl_news.categories IN (?)']));
            $filter->setValues(array_merge($filter->getValues(), StringUtil::deserialize($event->moduleModel->categories, true)));
        }

        $nextLabel = $this->translator->trans('huh.newsnavigation.article.next');
        $template->nextArticle = new Article(
            Template::once(fn() => $this->finder->findNextElement($filter)),
            $nextLabel
        );

        $previousLabel = $this->translator->trans('huh.newsnavigation.article.previous');
        $template->previousArticle = new Article(
            Template::once(fn() => $this->finder->findPreviousElement($filter)),
            $previousLabel
        );

        $template->nextArticleLabel = Template::once(function() use ($nextLabel) {
            trigger_deprecation(
                'heimrichhannot/contao-newsnavigation-bundle',
                '3.0',
                'Using the nextArticleLabel property is deprecated and will no longer be supported in version 4.0. Use the nextArticle.label property instead.'
            );
            return $this->translator->trans($nextLabel);
        });
        $template->previousArticleLabel = Template::once(function() use ($previousLabel) {
            trigger_deprecation(
                'heimrichhannot/contao-newsnavigation-bundle',
                '3.0',
                'Using the previousArticleLabel property is deprecated and will no longer be supported in version 4.0. Use the previousArticle.label property instead.'
            );
            return $this->translator->trans($previousLabel);
        });
    }
}