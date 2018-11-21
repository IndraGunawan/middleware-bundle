<?php

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class SkipControllerException extends \RuntimeException
{
    private $response;

    public function __construct(Response $response)
    {
        parent::__construct('');
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
