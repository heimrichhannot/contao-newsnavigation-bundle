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

        $template->nextArticle = Template::once(fn() => $this->finder->findNextElement($filter)->id);
        $template->previousArticle = Template::once(fn() => $this->finder->findPreviousElement($filter)->id);

        $template->nextArticleLabel = $this->translator->trans('huh.newsnavigation.article.next');
        $template->previousArticleLabel = $this->translator->trans('huh.newsnavigation.article.previous');
    }
}