<?php

namespace HeimrichHannot\NewsNavigationBundle\News;

use Contao\News;
use Contao\NewsModel;
use Contao\Template;
use Symfony\Component\Translation\TranslatableMessage;

class Article implements \Stringable
{
    private NewsModel|null $model;

    public function __construct(
        private readonly \Closure $evaluator,
        private readonly TranslatableMessage $message,
    ) {
    }

    public function model(): ?NewsModel
    {
        if (!isset($this->model)) {
            $this->model = call_user_func($this->evaluator);
        }

        return $this->model;
    }

    public function url(bool $absolute = false): string
    {
        if (!$this->model()) {
            return '';
        }
        return Template::once(fn() => News::generateNewsUrl($this->model(), $absolute))();
    }

    public function label(): TranslatableMessage
    {
        if (!$this->model()) {
            return new TranslatableMessage('');
        }
        return $this->message;
    }

    private function id(): int
    {
        if (!$this->model()) {
            return 0;
        }
        return $this->model()->id;
    }

    public function __toString(): string
    {
        if (!$this->model()) {
            return '';
        }
        return (string) $this->id();
    }
}