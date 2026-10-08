<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Polyfill\Php85\Tests\Php80\Fixtures;

#[\NoDiscard('nope')]
class NoDiscardOnClass
{
    #[\NoDiscard('method')]
    public function methodWithMessage()
    {
    }
}

#[\NoDiscard('keep')]
function nodiscard_function_with_message()
{
}

#[\NoDiscard]
function nodiscard_function_without_message()
{
}

#[\NoDiscard]
#[\NoDiscard]
function nodiscard_function_duplicate()
{
}
