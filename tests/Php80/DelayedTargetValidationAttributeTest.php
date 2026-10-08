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
use ReflectionParameter;
use Symfony\Polyfill\Php85\Tests\Php80\Fixtures\DelayedTargetSample;

require_once __DIR__.'/Fixtures/DelayedTargetFixtures.php';

class DelayedTargetValidationAttributeTest extends TestCase
{
    public function testAttributeFlagsAreTargetAll()
    {
        $attributes = (new ReflectionClass(\DelayedTargetValidation::class))->getAttributes(\Attribute::class);
        $this->assertCount(1, $attributes);
        $instance = $attributes[0]->newInstance();
        $this->assertSame(\Attribute::TARGET_ALL, $instance->flags);
    }

    public function testAppliedToClass()
    {
        $attributes = (new ReflectionClass(DelayedTargetSample::class))->getAttributes(\DelayedTargetValidation::class);
        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(\DelayedTargetValidation::class, $attributes[0]->newInstance());
    }

    public function testAppliedToMethod()
    {
        $attributes = (new ReflectionMethod(DelayedTargetSample::class, 'sampleMethod'))->getAttributes(\DelayedTargetValidation::class);
        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(\DelayedTargetValidation::class, $attributes[0]->newInstance());
    }

    public function testAppliedToProperty()
    {
        $attributes = (new ReflectionClass(DelayedTargetSample::class))->getProperty('sampleProperty')->getAttributes(\DelayedTargetValidation::class);
        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(\DelayedTargetValidation::class, $attributes[0]->newInstance());
    }

    public function testAppliedToParameter()
    {
        $method = new ReflectionMethod(DelayedTargetSample::class, 'sampleMethod');
        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $attributes = $parameters[0]->getAttributes(\DelayedTargetValidation::class);
        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(\DelayedTargetValidation::class, $attributes[0]->newInstance());
    }

    public function testAppliedToClassConstant()
    {
        $attributes = (new ReflectionClass(DelayedTargetSample::class))->getReflectionConstant('SAMPLE')->getAttributes(\DelayedTargetValidation::class);
        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(\DelayedTargetValidation::class, $attributes[0]->newInstance());
    }

    public function testAppliedToFunction()
    {
        $reflection = new ReflectionFunction('Symfony\\Polyfill\\Php85\\Tests\\Php80\\Fixtures\\delayed_target_function');
        $attributes = $reflection->getAttributes(\DelayedTargetValidation::class);
        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(\DelayedTargetValidation::class, $attributes[0]->newInstance());
    }
}
