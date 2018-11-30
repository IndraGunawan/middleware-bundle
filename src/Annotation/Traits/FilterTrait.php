<?php

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle\Annotation\Traits;

trait FilterTrait
{
    /**
     * @var string[]
     */
    protected $names;

    /**
     * @var string[]
     */
    protected $only;

    /**
     * @var string[]
     */
    protected $except;

    public function getNames(): array
    {
        return $this->names;
    }

    public function getOnly(): array
    {
        return $this->only;
    }

    public function getExcept(): array
    {
        return $this->except;
    }

    public function isSupportsMethod(string $method): bool
    {
        if (empty($this->only) && empty($this->except)) {
            return true;
        }

        if (\in_array($method, $this->only, true)) {
            return true;
        }

        if (!empty($this->except) && !\in_array($method, $this->except, true)) {
            return true;
        }

        return false;
    }
}
