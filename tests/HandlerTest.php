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

class HandlerTest extends TestCase
{
    public function testGetErrorHandlerReturnsClosure()
    {
        $closure = static function () {
        };
        set_error_handler($closure);
        try {
            $this->assertSame($closure, get_error_handler());
        } finally {
            restore_error_handler();
        }
    }

    public function testGetErrorHandlerReturnsInvokable()
    {
        $object = new TestHandlerInvokable();
        set_error_handler($object);
        try {
            $this->assertSame($object, get_error_handler());
        } finally {
            restore_error_handler();
        }
    }

    public function testGetErrorHandlerReturnsInstanceMethodArray()
    {
        $handler = new TestHandler();
        set_error_handler([$handler, 'handle']);
        try {
            $this->assertSame([$handler, 'handle'], get_error_handler());
        } finally {
            restore_error_handler();
        }
    }

    public function testGetErrorHandlerReturnsStaticMethodArray()
    {
        set_error_handler([TestHandler::class, 'handleStatic']);
        try {
            $this->assertSame([TestHandler::class, 'handleStatic'], get_error_handler());
        } finally {
            restore_error_handler();
        }
    }

    public function testGetErrorHandlerReturnsStaticMethodString()
    {
        $name = TestHandler::class.'::handleStatic';
        set_error_handler($name);
        try {
            $this->assertSame($name, get_error_handler());
        } finally {
            restore_error_handler();
        }
    }

    public function testGetErrorHandlerReturnsFunctionName()
    {
        set_error_handler('var_dump');
        try {
            $this->assertSame('var_dump', get_error_handler());
        } finally {
            restore_error_handler();
        }
    }

    public function testGetErrorHandlerReturnsNullWhenCleared()
    {
        set_error_handler(null);
        try {
            $this->assertNull(get_error_handler());
        } finally {
            restore_error_handler();
        }
    }

    public function testGetErrorHandlerLeavesHandlerInstalled()
    {
        $calls = [];
        $handler = static function ($errno, $errstr) use (&$calls) {
            $calls[] = [$errno, $errstr];

            return true;
        };
        set_error_handler($handler);
        try {
            get_error_handler();
            trigger_error('polyfill-marker', E_USER_NOTICE);
            $this->assertSame([[E_USER_NOTICE, 'polyfill-marker']], $calls);
        } finally {
            restore_error_handler();
        }
    }

    public function testGetErrorHandlerPreservesStack()
    {
        $h1 = static function () {
        };
        $h2 = static function () {
        };
        set_error_handler($h1);
        set_error_handler($h2);
        try {
            $this->assertSame($h2, get_error_handler());
            restore_error_handler();
            $this->assertSame($h1, get_error_handler());
        } finally {
            restore_error_handler();
        }
    }

    public function testGetErrorHandlerRestoresOuterHandler()
    {
        $outer = static function () {
        };
        set_error_handler($outer);
        try {
            $this->assertSame($outer, get_error_handler());
        } finally {
            restore_error_handler();
        }
    }

    public function testGetExceptionHandlerReturnsClosure()
    {
        $closure = static function () {
        };
        set_exception_handler($closure);
        try {
            $this->assertSame($closure, get_exception_handler());
        } finally {
            restore_exception_handler();
        }
    }

    public function testGetExceptionHandlerReturnsInvokable()
    {
        $object = new TestHandlerInvokable();
        set_exception_handler($object);
        try {
            $this->assertSame($object, get_exception_handler());
        } finally {
            restore_exception_handler();
        }
    }

    public function testGetExceptionHandlerReturnsInstanceMethodArray()
    {
        $handler = new TestHandler();
        set_exception_handler([$handler, 'handle']);
        try {
            $this->assertSame([$handler, 'handle'], get_exception_handler());
        } finally {
            restore_exception_handler();
        }
    }

    public function testGetExceptionHandlerReturnsStaticMethodArray()
    {
        set_exception_handler([TestHandler::class, 'handleStatic']);
        try {
            $this->assertSame([TestHandler::class, 'handleStatic'], get_exception_handler());
        } finally {
            restore_exception_handler();
        }
    }

    public function testGetExceptionHandlerReturnsStaticMethodString()
    {
        $name = TestHandler::class.'::handleStatic';
        set_exception_handler($name);
        try {
            $this->assertSame($name, get_exception_handler());
        } finally {
            restore_exception_handler();
        }
    }

    public function testGetExceptionHandlerReturnsFunctionName()
    {
        set_exception_handler('var_dump');
        try {
            $this->assertSame('var_dump', get_exception_handler());
        } finally {
            restore_exception_handler();
        }
    }

    public function testGetExceptionHandlerReturnsNullWhenCleared()
    {
        set_exception_handler(null);
        try {
            $this->assertNull(get_exception_handler());
        } finally {
            restore_exception_handler();
        }
    }

    public function testGetExceptionHandlerLeavesHandlerInstalled()
    {
        $handler = static function () {
        };
        $probe = static function () {
        };
        set_exception_handler($handler);
        try {
            get_exception_handler();
            $previous = set_exception_handler($probe);
            $this->assertSame($handler, $previous);
            restore_exception_handler();
        } finally {
            restore_exception_handler();
        }
    }

    public function testGetExceptionHandlerPreservesStack()
    {
        $h1 = static function () {
        };
        $h2 = static function () {
        };
        set_exception_handler($h1);
        set_exception_handler($h2);
        try {
            $this->assertSame($h2, get_exception_handler());
            restore_exception_handler();
            $this->assertSame($h1, get_exception_handler());
        } finally {
            restore_exception_handler();
        }
    }

    public function testGetExceptionHandlerRestoresOuterHandler()
    {
        $outer = static function () {
        };
        set_exception_handler($outer);
        try {
            $this->assertSame($outer, get_exception_handler());
        } finally {
            restore_exception_handler();
        }
    }
}

class TestHandler
{
    public static function handleStatic()
    {
    }

    public function handle()
    {
    }
}

class TestHandlerInvokable
{
    public function __invoke()
    {
    }
}
