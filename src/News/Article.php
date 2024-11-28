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
        private readonly string|TranslatableMessage $message,
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

    public function label(): TranslatableMessage|string
    {
        if (!$this->model()) {
            return '';
        }
        return $this->message;
    }

    public function __get(string $name)
    {
        if (!$this->model()) {
            return null;
        }

        return match ($name) {
            'model' => $this->model(),
            'url' => $this->url(),
            'label' => $this->label(),
            default => $this->model()->$name,
        };
    }

    public function __isset(string $name): bool
    {
        if (!$this->model()) {
            return false;
        }

        return match ($name) {
            'model', 'url', 'label' => true,
            default => $this->model()->$name !== null,
        };
    }

    public function __toString(): string
    {
        if (!$this->model()) {
            return '';
        }
        return (string) $this->model()->id;
    }
}