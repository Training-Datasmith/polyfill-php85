<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Polyfill\Php85\Tests\Php80;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use Symfony\Polyfill\Php85\Tests\Php80\Fixtures\NoDiscardOnClass;

require_once __DIR__.'/Fixtures/NoDiscardFixtures.php';

class NoDiscardAttributeTest extends TestCase
{
    public function testAttributeFlags()
    {
        $attributes = (new ReflectionClass(\NoDiscard::class))->getAttributes(\Attribute::class);
        $this->assertCount(1, $attributes);
        $instance = $attributes[0]->newInstance();
        $this->assertSame(
            \Attribute::TARGET_METHOD | \Attribute::TARGET_FUNCTION,
            $instance->flags
        );
    }

    public function testMessageRoundTripOnFunction()
    {
        $reflection = new ReflectionFunction('Symfony\\Polyfill\\Php85\\Tests\\Php80\\Fixtures\\nodiscard_function_with_message');
        $attributes = $reflection->getAttributes(\NoDiscard::class);
        $this->assertCount(1, $attributes);
        $this->assertSame('keep', $attributes[0]->newInstance()->message);
    }

    public function testMessageRoundTripOnMethod()
    {
        $reflection = new ReflectionMethod(NoDiscardOnClass::class, 'methodWithMessage');
        $attributes = $reflection->getAttributes(\NoDiscard::class);
        $this->assertCount(1, $attributes);
        $this->assertSame('method', $attributes[0]->newInstance()->message);
    }

    public function testOmittedMessageRoundTripsAsNull()
    {
        $reflection = new ReflectionFunction('Symfony\\Polyfill\\Php85\\Tests\\Php80\\Fixtures\\nodiscard_function_without_message');
        $attributes = $reflection->getAttributes(\NoDiscard::class);
        $this->assertCount(1, $attributes);
        $this->assertNull($attributes[0]->newInstance()->message);
    }

    public function testCannotTargetAClass()
    {
        $attributes = (new ReflectionClass(NoDiscardOnClass::class))->getAttributes(\NoDiscard::class);
        $this->assertCount(1, $attributes);
        try {
            $attributes[0]->newInstance();
            $this->fail('Expected instantiation of NoDiscard on a class target to throw.');
        } catch (\Throwable $exception) {
            $this->assertStringContainsString('NoDiscard', $exception->getMessage());
        }
    }

    public function testIsNotRepeatable()
    {
        $meta = (new ReflectionClass(\NoDiscard::class))->getAttributes(\Attribute::class);
        $this->assertCount(1, $meta);
        $flags = $meta[0]->newInstance()->flags;
        $this->assertSame(0, $flags & \Attribute::IS_REPEATABLE);
    }
}
