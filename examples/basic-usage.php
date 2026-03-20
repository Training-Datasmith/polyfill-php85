<?php

declare(strict_types=1);

/**
 * Example: Using symfony/polyfill-php85 functions.
 *
 * This polyfill provides PHP 8.5 functions for PHP 8.1, 8.2, 8.3, and 8.4.
 * On PHP 8.5+, native implementations are used automatically.
 *
 * Install:
 *   composer require symfony/polyfill-php85
 */

// --- get_error_handler(): retrieve the current error handler ---
set_error_handler(function (int $errno, string $errstr): bool {
    echo "Error [$errno]: $errstr\n";
    return true;
});

$handler = get_error_handler();
var_dump(is_callable($handler)); // bool(true)

// Restore and verify null when no handler is set
restore_error_handler();
$noHandler = get_error_handler();
var_dump($noHandler); // NULL

// --- get_exception_handler(): retrieve the current exception handler ---
set_exception_handler(function (\Throwable $e): void {
    echo "Uncaught: " . $e->getMessage() . "\n";
});

$exHandler = get_exception_handler();
var_dump(is_callable($exHandler)); // bool(true)

restore_exception_handler();

// --- array_first(): get first element without resetting internal pointer ---
$items = ['a' => 1, 'b' => 2, 'c' => 3];

// Unlike reset(), array_first() does not modify the array pointer
$first = array_first($items);
var_dump($first); // int(1)

$emptyFirst = array_first([]);
var_dump($emptyFirst); // NULL

// --- array_last(): get last element without resetting internal pointer ---
$last = array_last($items);
var_dump($last); // int(3)

$emptyLast = array_last([]);
var_dump($emptyLast); // NULL

// --- Practical use: safe first/last without resetting pointer ---
$numbers = [10, 20, 30, 40, 50];
next($numbers); // advance pointer to 20

$first = array_first($numbers);
$last  = array_last($numbers);

var_dump($first); // int(10)  — still returns first, pointer not affected by array_first
var_dump($last);  // int(50)
var_dump(current($numbers)); // int(20) — internal pointer unchanged
