# PHPUnit 4.8 Fixer

Function `each` is deprecated from PHP 7.2, it causes errors in PHPUnit 4.8:

```
Deprecated: The each() function is deprecated.
This message will be suppressed on further calls in (...)/phpunit/src/Util/Getopt.php
```

This library fixes the above issue.

## Installation

```
composer require awesomite/phpunit-4.8-fixer
```

## Usage

Execute the following command once before executing tests:

```
vendor/bin/phpunit-4.8-fixer
```
