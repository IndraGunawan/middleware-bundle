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
interface AfterFilterInterface extends MiddlewareInterface
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $controller
     * @param int|null $requestType
     *
     * @return void|Response
     */
    public function onAfterFilter(Request $request, Response $response, array $controller, ?int $requestType);
}
