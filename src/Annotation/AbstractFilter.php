<?php

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle\Annotation;

use Webmozart\Assert\Assert;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
abstract class AbstractFilter
{
    /**
     * @var array
     */
    protected $names;

    /**
     * @var array
     */
    protected $only;

    /**
     * @var array
     */
    protected $except;

    public function __construct(array $values)
    {
        $names = (array) ($values['value'] ?? []);
        $only = (array) ($values['only'] ?? []);
        $except = (array) ($values['except'] ?? []);

        Assert::minCount($names, 1);
        Assert::allStringNotEmpty($names);
        Assert::allStringNotEmpty($only);
        Assert::allStringNotEmpty($except);

        unset($values['value'], $values['only'], $values['except']);
        if (\count($values) > 0) {
            throw new \InvalidArgumentException(sprintf('The annotation @%s does not have a property named "%s".', \get_class($this), implode('", "', array_keys($values))));
        }

        if ($result = \array_intersect($only, $except)) {
            throw new \InvalidArgumentException(sprintf('You cannot put "%s" in "only" and "except" at the same time.', implode('", "', $result)));
        }

        $this->names = $names;
        $this->only = $only;
        $this->except = $except;
    }

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
