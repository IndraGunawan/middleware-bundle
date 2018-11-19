<?php

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle\DependencyInjection;

use Indragunawan\MiddlewareBundle\Middleware\AfterFilterInterface;
use Indragunawan\MiddlewareBundle\Middleware\BeforeFilterInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class IndragunawanMiddlewareExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->registerForAutoconfiguration(BeforeFilterInterface::class)
            ->addTag('indragunawan.middleware', ['event' => 'before_filter']);

        $container->registerForAutoconfiguration(AfterFilterInterface::class)
            ->addTag('indragunawan.middleware', ['event' => 'after_filter']);
    }
}
