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

namespace Indragunawan\MiddlewareBundle\Middleware;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
interface MiddlewareInterface
{
    /**
     * @return string|array
     */
    public static function supports();
}
