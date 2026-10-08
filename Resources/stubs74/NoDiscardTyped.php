<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!\class_exists('NoDiscard', false)) {
    final class NoDiscard
    {
    public ?string $message;

    public function __construct(?string $message = null)
    {
        $this->message = $message;
    }
    }
}
