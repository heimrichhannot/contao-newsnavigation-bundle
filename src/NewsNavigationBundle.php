<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
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
    public function getContainerExtension()
    {
        return new NewsNavigationExtension();
    }
}