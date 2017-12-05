<?php

/*
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\NewsNavigationBundle\Test;

use HeimrichHannot\NewsNavigationBundle\DependencyInjection\NewsNavigationExtension;
use HeimrichHannot\NewsNavigationBundle\NewsNavigationBundle;
use PHPUnit\Framework\TestCase;

class NewsNavigationBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new NewsNavigationBundle();
        $this->assertInstanceOf(NewsNavigationBundle::class, $bundle);
    }

    public function testGetContainerExtension()
    {
        $bundle = new NewsNavigationBundle();
        $this->assertInstanceOf(
            NewsNavigationExtension::class,
            $bundle->getContainerExtension()
        );
    }
}
