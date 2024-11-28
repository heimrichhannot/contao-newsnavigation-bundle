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
use Symfony\Component\Translation\TranslatableMessage;
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

        $this->eventDispatcher->dispatch(new NewsNavigationFilterEvent($filter, $module->getModel()));

        $template->nextArticle = new Article(
            Template::once(fn() => $this->finder->findNextElement($filter)),
            new TranslatableMessage('huh.newsnavigation.article.next')
        );
        $template->previousArticle = new Article(
            Template::once(fn() => $this->finder->findPreviousElement($filter)),
            new TranslatableMessage('huh.newsnavigation.article.previous')
        );

        $template->nextArticleLabel = Template::once(function() {
            trigger_deprecation(
                'heimrichhannot/contao-newsnavigation-bundle',
                '3.0',
                'Using the nextArticleLabel property is deprecated and will no longer be supported in version 4.0. Use the nextArticle.label property instead.'
            );
            return $this->translator->trans('huh.newsnavigation.article.next');
        });
        $template->previousArticleLabel = Template::once(function() {
            trigger_deprecation(
                'heimrichhannot/contao-newsnavigation-bundle',
                '3.0',
                'Using the previousArticleLabel property is deprecated and will no longer be supported in version 4.0. Use the previousArticle.label property instead.'
            );
            return $this->translator->trans('huh.newsnavigation.article.previous');
        });
    }
}