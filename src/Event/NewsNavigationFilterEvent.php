<?php

namespace HeimrichHannot\NewsNavigationBundle\Event;

use Contao\Model;
use HeimrichHannot\NewsNavigationBundle\Filter\Filter;
use Symfony\Contracts\EventDispatcher\Event;

class NewsNavigationFilterEvent extends Event
{
    public function __construct(
        public readonly Filter $filter,
        public readonly Model $model,
    ) {
    }
}