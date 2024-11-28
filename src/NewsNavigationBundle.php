<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\NewsNavigationBundle;

use HeimrichHannot\NewsNavigationBundle\DependencyInjection\NewsNavigationExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NewsNavigationBundle extends Bundle
{
    /**
     * Returns the bundle's container extension.
     *
     * @throws \LogicException
     *
     * @return ExtensionInterface|null The container extension
     */
    public function getContainerExtension(): ExtensionInterface|null
    {
        return new NewsNavigationExtension();
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
