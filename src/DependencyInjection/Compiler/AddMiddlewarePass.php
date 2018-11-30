<?php

declare(strict_types=1);

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle\DependencyInjection\Compiler;

use Indragunawan\MiddlewareBundle\Middleware\MiddlewareInterface;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class AddMiddlewarePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('indragunawan_middleware.middleware')) {
            return;
        }

        $definition = $container->findDefinition('indragunawan_middleware.middleware');
        foreach ($container->findTaggedServiceIds('indragunawan.middleware') as $id => $attrs) {
            foreach ($attrs as $attr) {
                $class = $container->getDefinition($id)->getClass();
                if (!$r = $container->getReflectionClass($class)) {
                    throw new InvalidArgumentException(sprintf('Class "%s" used for service "%s" cannot be found.', $class, $id));
                } elseif (!$r->isSubclassOf(MiddlewareInterface::class)) {
                    throw new InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, MiddlewareInterface::class));
                } elseif (empty($attr['event'])) {
                    throw new InvalidArgumentException('"event" tag attribute must be set');
                }

                $priority = 0;
                $name = null;
                $supports = forward_static_call([$class, 'supports']);
                if (\is_string($supports)) {
                    $name = $supports;
                } elseif (\is_string($supports[0])) {
                    $name = $supports[0];
                    $priority = isset($supports[1]) ? $supports[1] : 0;
                } else {
                    throw new InvalidArgumentException(sprintf('Invalid return format "supports" of class "%s".', $class));
                }

                $definition->addMethodCall('addMiddleware', [$attr['event'], $name, new ServiceClosureArgument(new Reference($id)), $priority]);
            }
        }
    }
}
