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

class DelayedTargetValidationTest extends TestCase
{
    public function testClassIsGlobalAndFinal()
    {
        $this->assertTrue(class_exists(\DelayedTargetValidation::class));
        $reflection = new ReflectionClass(\DelayedTargetValidation::class);
        $this->assertSame('DelayedTargetValidation', $reflection->getName());
        $this->assertTrue($reflection->isFinal());
        $this->assertInstanceOf(\DelayedTargetValidation::class, new \DelayedTargetValidation());
    }

    /**
     * @requires PHP >= 8.1
     */
    public function testConstructorRejectsArguments()
    {
        $this->expectException(\ArgumentCountError::class);
        new \DelayedTargetValidation(1);
    }
}
