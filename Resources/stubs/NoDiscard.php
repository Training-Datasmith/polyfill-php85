<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (\PHP_VERSION_ID >= 80500) {
    return;
}

if (\PHP_VERSION_ID >= 80000) {
    require_once __DIR__.'/../stubs74/NoDiscard.php';

    return;
}

if (\PHP_VERSION_ID >= 70400) {
    require_once __DIR__.'/../stubs74/NoDiscardTyped.php';

    return;
}

require_once __DIR__.'/../stubs72/NoDiscard.php';
