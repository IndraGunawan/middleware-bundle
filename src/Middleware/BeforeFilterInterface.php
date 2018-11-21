<?php

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
interface BeforeFilterInterface extends MiddlewareInterface
{
    /**
     * @param Request  $request
     * @param array    $controller
     * @param int|null $requestType
     *
     * @return void|Response
     */
    public function onBeforeFilter(Request $request, array $controller, ?int $requestType);
}
