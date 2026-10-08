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
use ReflectionClass;
use TypeError;

class NoDiscardTest extends TestCase
{
    public function testClassIsGlobalAndFinal()
    {
        $this->assertTrue(class_exists(\NoDiscard::class));
        $reflection = new ReflectionClass(\NoDiscard::class);
        $this->assertSame('NoDiscard', $reflection->getName());
        $this->assertSame('', $reflection->getNamespaceName());
        $this->assertTrue($reflection->isFinal());
    }

    public function testConstructorDefaultsMessageToNull()
    {
        $this->assertNull((new \NoDiscard())->message);
    }

    public function testConstructorStoresStringMessage()
    {
        $this->assertSame('keep', (new \NoDiscard('keep'))->message);
    }

    public function testConstructorStoresExplicitNull()
    {
        $this->assertNull((new \NoDiscard(null))->message);
    }

    public function testConstructorRejectsNonString()
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('must be of type ?string');
        new \NoDiscard([]);
    }

    /**
     * @requires PHP >= 8.1
     */
    public function testConstructorRejectsExtraArgument()
    {
        $this->expectException(\ArgumentCountError::class);
        new \NoDiscard('a', 'b');
    }

    public function testPropertyIsPublicAndNamedMessage()
    {
        $property = (new ReflectionClass(\NoDiscard::class))->getProperty('message');
        $this->assertTrue($property->isPublic());
        $this->assertSame('message', $property->getName());
    }

    /**
     * @requires PHP >= 7.4
     */
    public function testPropertyTypeIsNullableString()
    {
        $property = (new ReflectionClass(\NoDiscard::class))->getProperty('message');
        $this->assertTrue($property->hasType());
        $type = $property->getType();
        $this->assertSame('string', $type->getName());
        $this->assertTrue($type->allowsNull());
    }
}
