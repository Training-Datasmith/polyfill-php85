<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

final class NoDiscard
{
    public $message;

    public function __construct($message = null)
    {
        if (null === $message) {
            $this->message = null;

            return;
        }

        if (\is_string($message)) {
            $this->message = $message;

            return;
        }

        if (\is_int($message) || \is_float($message) || \is_bool($message)) {
            $this->message = (string) $message;

            return;
        }

        if (\is_object($message) && \method_exists($message, '__toString')) {
            $this->message = (string) $message;

            return;
        }

        throw new \TypeError('NoDiscard::__construct(): Argument #1 ($message) must be of type ?string');
    }
}
