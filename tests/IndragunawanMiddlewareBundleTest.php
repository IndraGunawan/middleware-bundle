<?php

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle\Tests;

use Indragunawan\MiddlewareBundle\IndragunawanMiddlewareBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class IndragunawanMiddlewareBundleTest extends TestCase
{
    public function testBuild()
    {
        $container = new ContainerBuilder();

        $compilerPassCount = count($container->getCompilerPassConfig()->getPasses());

        $bundle = new IndragunawanMiddlewareBundle();
        $bundle->build($container);

        self::assertSame($compilerPassCount + 1, count($container->getCompilerPassConfig()->getPasses()));
    }
}
