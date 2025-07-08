[![License](https://img.shields.io/github/license/imponeer/object-errors.svg?maxAge=2592000)](LICENSE)
[![Packagist](https://img.shields.io/packagist/v/imponeer/object-errors.svg)](https://packagist.org/packages/imponeer/object-errors) [![PHP](https://img.shields.io/packagist/php-v/imponeer/object-errors.svg)](http://php.net)
[![Packagist](https://img.shields.io/packagist/dm/imponeer/object-errors.svg)](https://packagist.org/packages/imponeer/object-errors)

# Object Errors

A PHP library for collecting and managing errors associated with objects. Useful for tracking validation or processing errors in a structured way.

## Installation

Install via [Composer](https://getcomposer.org):

```bash
composer require imponeer/object-errors
```

Alternatively, you can manually include the files from the `src/` directory.

## Usage

This library allows you to attach an error collection to your objects and manage errors easily.

### Using as a property
Below is a simple usage example by directly creating an `ErrorsCollection` instance:

```php
use Imponeer\ObjectErrors\ErrorsCollection;

class MyObject {
    /**
     * @var ErrorsCollection|null
     */
    public $errors = null;

    public function __construct() {
        $this->errors = new ErrorsCollection();
    }

    public function doSomething() {
        // Example logic
        if ($failed) {
            $this->errors->add("Some error");
        }
    }

    public function render() {
        if ($this->errors->isEmpty()) {
            return 'Everything fine';
        } else {
            return $this->errors->getHtml();
        }
    }
}
```

### Using as a trait
You can also use the provided `ErrorsTrait` to quickly add error handling to your classes:

```php
use Imponeer\ObjectErrors\ErrorsTrait;

class MyObject {
    use ErrorsTrait;

    public function doSomething() {
        if ($failed) {
            $this->setErrors("Some error");
        }
    }

    public function render() {
        if ($this->hasError()) {
            return $this->getHtmlErrors();
        }
        return 'Everything fine';
    }
}
```

## Development

Below are useful commands for development. Each command should be run from the project root directory.

Run tests using PHPUnit:
```bash
composer test
```

Check code style using PHP_CodeSniffer:
```bash
composer phpcs
```

Automatically fix code style issues:
```bash
composer phpcbf
```

Run static analysis using PHPStan:
```bash
composer phpstan
```

## API Documentation

For detailed API documentation, please visit the [Object Errors Wiki](https://github.com/imponeer/object-errors/wiki).

## How to contribute?

Contributions are welcome! If you want to add new features or fix bugs, please fork the repository, make your changes, and submit a pull request.

If you find any bugs or have questions, please use the [issues tab](https://github.com/imponeer/object-errors/issues) to report them or ask questions.
