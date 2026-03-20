# Architecture: polyfill-php85

## Purpose

Backports PHP 8.5 functions, constants, and attribute stubs to PHP 8.2, 8.3, and 8.4.
Enables libraries to target PHP 8.5 features while remaining installable on older PHP 8.x
versions.

## Directory Structure

```
Php85.php       # Pure-PHP implementations of new PHP 8.5 functions as static methods
bootstrap.php   # Defines global functions/constants from PHP 8.5 if running on PHP < 8.5
Resources/
  stubs/
    DelayedTargetValidation.php  # Stub for the #[DelayedTargetValidation] attribute
    NoDiscard.php                # Stub for the #[NoDiscard] attribute (new in PHP 8.5)
```

## Key Design Decisions

`#[NoDiscard]` is a new attribute for PHP 8.5 that marks functions whose return values
must not be ignored. The stub allows codebases to add these annotations for linters/IDEs
while remaining installable on PHP < 8.5.

## Extension Points

None — drop-in function and class polyfill.
