[![License](https://img.shields.io/github/license/imponeer/object-errors.svg?maxAge=2592000)](LICENSE)
[![GitHub release](https://img.shields.io/github/release/imponeer/object-errors.svg?maxAge=2592000)](https://github.com/imponeer/object-errors/releases) [![Build Status](https://travis-ci.org/imponeer/object-errors.svg?branch=master)](https://travis-ci.org/imponeer/object-errors) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/imponeer/object-errors/badges/quality-score.png)](https://scrutinizer-ci.com/g/imponeer/object-errors/) 
![PHP from Packagist](https://img.shields.io/packagist/php-v/imponeer/object-errors.svg)


# Object Errors

Library that can be used for collecting errors on objects.

## Installation

To install and use this package, we recommend to use [Composer](https://getcomposer.org):

```bash
composer require imponeer/object-errors
```

Otherwise you need to include manualy files from `src/` directory. 

## Example

```php

use Imponeer/ObjectErrors/ErrorsCollection;

class Object {

   /**
    * Errors variable
    *
    * @var null|ErrorsCollection
    */
   public $errors = null;
   
   /**
    * Constructor (binds new instance of ErrorsCollection to $errors var)
    */
   public function __constructor() {
       $this->errors = new ErrorsCollection();
   }
   
   /**
    * This method do something
    */
   public function doSomething() {
      // here we should do something
      if ($failed) {
         $this->errors->add("Some error");
      }
   }
   
   /**
    * Renders object content
    *
    * @return string
    */
   public function render() {
     if ($this->errors->isEmpty()) {
        return 'Everything fine';
     } else {
        return $this->errors->getHTML();
     }
   }

}

```
