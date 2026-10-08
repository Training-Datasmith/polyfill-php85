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

#[\DelayedTargetValidation]
class DelayedTargetSample
{
    public const SAMPLE = 'x';

    #[\DelayedTargetValidation]
    public $sampleProperty;

    #[\DelayedTargetValidation]
    public function sampleMethod(
        #[\DelayedTargetValidation]
        $param
    ) {
    }
}

#[\DelayedTargetValidation]
function delayed_target_function()
{
}
