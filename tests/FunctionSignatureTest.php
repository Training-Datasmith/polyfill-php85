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
use ReflectionFunction;

class FunctionSignatureTest extends TestCase
{
    public function testArrayFirstSignature()
    {
        $function = new ReflectionFunction('array_first');
        $parameters = $function->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertSame('array', $parameters[0]->getName());
        $this->assertTrue($parameters[0]->hasType());
        $this->assertSame('array', (string) $parameters[0]->getType());
        $this->assertFalse($parameters[0]->getType()->allowsNull());
        $this->assertFalse($parameters[0]->isPassedByReference());
    }

    public function testArrayLastSignature()
    {
        $function = new ReflectionFunction('array_last');
        $parameters = $function->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertSame('array', $parameters[0]->getName());
        $this->assertTrue($parameters[0]->hasType());
        $this->assertSame('array', (string) $parameters[0]->getType());
        $this->assertFalse($parameters[0]->getType()->allowsNull());
        $this->assertFalse($parameters[0]->isPassedByReference());
    }

    public function testGetErrorHandlerSignature()
    {
        $function = new ReflectionFunction('get_error_handler');
        $this->assertCount(0, $function->getParameters());
        $returnType = $function->getReturnType();
        $this->assertTrue($function->hasReturnType());
        $this->assertTrue($returnType->allowsNull());
        $this->assertSame('callable', ltrim((string) $returnType, '?'));
    }

    public function testGetExceptionHandlerSignature()
    {
        $function = new ReflectionFunction('get_exception_handler');
        $this->assertCount(0, $function->getParameters());
        $returnType = $function->getReturnType();
        $this->assertTrue($function->hasReturnType());
        $this->assertTrue($returnType->allowsNull());
        $this->assertSame('callable', ltrim((string) $returnType, '?'));
    }
}
