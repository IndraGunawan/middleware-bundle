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

namespace Indragunawan\MiddlewareBundle;

use Indragunawan\MiddlewareBundle\DependencyInjection\Compiler\AddMiddlewarePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class IndragunawanMiddlewareBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder   $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddMiddlewarePass());
    }
}
