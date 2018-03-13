[![License](https://img.shields.io/github/license/IPFLibraries/object-errors.svg?maxAge=2592000)](License.txt)
[![GitHub release](https://img.shields.io/github/release/IPFLibraries/object-errors.svg?maxAge=2592000)](https://github.com/IPFLibraries/object-errors/releases) [![Build Status](https://travis-ci.org/IPFLibraries/object-errors.svg?branch=master)](https://travis-ci.org/IPFLibraries/object-errors) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/IPFLibraries/object-errors/badges/quality-score.png)](https://scrutinizer-ci.com/g/IPFLibraries/object-errors/)

# Object Errors

Library that can be used for collecting errors on objects.

## Installation

```bash
composer require ipf-libraries/object-errors
```

## Usage

Simply way to start using is this lib is here:

```php

use IPFLibraries/ObjectErrors/ErrorsCollection;

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
