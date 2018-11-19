<?php

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle\Tests\Annotation;

use Indragunawan\MiddlewareBundle\Annotation\AfterFilter;
use Indragunawan\MiddlewareBundle\Annotation\BeforeFilter;
use PHPUnit\Framework\TestCase;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class FilterTest extends TestCase
{
    /**
     * @dataProvider validArgumentProvider
     *
     * @param mixed $values
     */
    public function testValidArgument($values)
    {
        $beforeFilter = new BeforeFilter($values);
        $this->assertSame((array) $values['value'], $beforeFilter->getNames());
        $this->assertSame((array) ($values['only'] ?? []), $beforeFilter->getOnly());
        $this->assertSame((array) ($values['except'] ?? []), $beforeFilter->getExcept());
    }

    /**
     * @dataProvider invalidArgumentProvider
     *
     * @param mixed $values
     * @param mixed $expectedErrorMessage
     */
    public function testInvalidArgumentBeforeFilter($values, $expectedErrorMessage)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedErrorMessage);

        new AfterFilter($values);
    }

    public function testIsSupportsMethod()
    {
        $filter = new BeforeFilter(['value' => 'check']);
        $this->assertTrue($filter->isSupportsMethod('index'));

        $filter = new AfterFilter(['value' => 'check', 'only' => 'index']);
        $this->assertTrue($filter->isSupportsMethod('index'));
        $this->assertFalse($filter->isSupportsMethod('delete'));

        $filter = new BeforeFilter(['value' => 'check', 'except' => 'index']);
        $this->assertFalse($filter->isSupportsMethod('index'));
        $this->assertTrue($filter->isSupportsMethod('delete'));
    }

    public function validArgumentProvider()
    {
        return [
            [['value' => 'check']],
            [['value' => 'check', 'only' => 'index']],
            [['value' => 'check', 'except' => 'delete']],
            [['value' => 'check', 'only' => 'index', 'except' => 'delete']],
            [['value' => ['check', 'check2'], 'only' => ['index', 'update'], 'except' => ['delete', 'create']]],
        ];
    }

    public function invalidArgumentProvider()
    {
        return [
            [['value' => ''], 'Expected a different value than ""'],
            [['value' => 0], 'Expected a string. Got: integer'],
            [['value' => [0, 1]], 'Expected a string. Got: integer'],
            [['only' => 'index'], 'Expected an array to contain at least 1 elements. Got: 0'],
            [['value' => 'check', 'only' => ''], 'Expected a different value than ""'],
            [['value' => 'check', 'only' => [0, 1]], 'Expected a string. Got: integer'],
            [['value' => 'check', 'except' => ''], 'Expected a different value than ""'],
            [['value' => 'check', 'except' => [0, 1]], 'Expected a string. Got: integer'],
            [['value' => 'check', 'only' => ['index', 'update'], 'except' => ['index', 'create']], 'You cannot put "index" in "only" and "except" at the same time'],
            [['value' => 'check', 'other' => 'value'], 'does not have a property named "other"'],
        ];
    }
}
