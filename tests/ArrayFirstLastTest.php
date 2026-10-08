<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Polyfill\Php85\Tests;

use PHPUnit\Framework\TestCase;
use stdClass;

class ArrayFirstLastTest extends TestCase
{
    public function testArrayFirstReturnsNullForEmptyArray()
    {
        $this->assertNull(array_first([]));

        $array = [1, 2, 3];
        unset($array[0], $array[1], $array[2]);
        $this->assertNull(array_first($array));
    }

    public function testArrayLastReturnsNullForEmptyArray()
    {
        $this->assertNull(array_last([]));

        $array = [1, 2, 3];
        unset($array[0], $array[1], $array[2]);
        $this->assertNull(array_last($array));
    }

    public function testArrayFirstKeepsLeadingNull()
    {
        $this->assertNull(array_first([null, 'x']));
    }

    public function testArrayLastKeepsTrailingNull()
    {
        $this->assertNull(array_last(['x', null]));
    }

    public function testArrayFirstKeepsFalseZeroAndEmptyString()
    {
        $this->assertFalse(array_first([false]));
        $this->assertSame(0, array_first([0]));
        $this->assertSame('', array_first(['']));
    }

    public function testArrayLastKeepsFalseZeroAndEmptyString()
    {
        $this->assertFalse(array_last([false]));
        $this->assertSame(0, array_last([0]));
        $this->assertSame('', array_last(['']));
    }

    public function testFollowsInsertionOrderNotKeyOrder()
    {
        $array = [1 => 'a', 0 => 'b', 3 => 'c', 2 => 'd'];
        $this->assertSame('a', array_first($array));
        $this->assertSame('d', array_last($array));
    }

    public function testStringKeys()
    {
        $array = ['a' => 'a1', 'b' => 'b1', 'c' => 'c1'];
        $this->assertSame('a1', array_first($array));
        $this->assertSame('c1', array_last($array));
    }

    public function testSkipsHoles()
    {
        $array = [10, 20, 30];
        unset($array[1]);
        $this->assertSame(10, array_first($array));
        $this->assertSame(30, array_last($array));
    }

    public function testNegativeKeys()
    {
        $array = [-1 => 'z', 5 => 'y'];
        $this->assertSame('z', array_first($array));
        $this->assertSame('y', array_last($array));
    }

    public function testObjectsByIdentity()
    {
        $object = new stdClass();
        $this->assertSame($object, array_first([$object]));
        $this->assertSame($object, array_last([$object]));
    }

    public function testNestedArrays()
    {
        $nested = [];
        $this->assertSame($nested, array_first([100 => $nested]));
        $this->assertSame($nested, array_last([100 => $nested]));
    }

    public function testSingleElement()
    {
        $this->assertSame('only', array_first(['only']));
        $this->assertSame('only', array_last(['only']));
    }

    public function testReturnedStringIsNotALiveReferenceForFirst()
    {
        $s = 'hello';
        $got = array_first([&$s]);
        $s = 'changed';
        $this->assertSame('hello', $got);
    }

    public function testReturnedStringIsNotALiveReferenceForLast()
    {
        $s = 'hello';
        $got = array_last([&$s]);
        $s = 'changed';
        $this->assertSame('hello', $got);
    }

    public function testDoesNotMoveInternalPointer()
    {
        $array = ['a', 'b', 'c'];
        next($array);
        $this->assertSame('b', current($array));
        $this->assertSame('a', array_first($array));
        $this->assertSame('c', array_last($array));
        $this->assertSame('b', current($array));
    }

    public function testDoesNotModifyTheArray()
    {
        $array = ['a', 'b', 'c'];
        $before = $array;
        array_first($array);
        array_last($array);
        $this->assertSame($before, $array);
    }

    public function testArrayFirstRejectsNonArray()
    {
        $this->expectException(\TypeError::class);
        array_first('nope');
    }

    public function testArrayFirstRejectsObject()
    {
        $this->expectException(\TypeError::class);
        array_first(new stdClass());
    }

    public function testArrayLastRejectsNonArray()
    {
        $this->expectException(\TypeError::class);
        array_last('nope');
    }

    public function testArrayLastRejectsObject()
    {
        $this->expectException(\TypeError::class);
        array_last(new stdClass());
    }
}
