<?php

namespace Imponeer\ObjectErrors\Tests;

use Imponeer\ObjectErrors\ErrorsTrait;
use PHPUnit\Framework\TestCase;

class ErrorsTraitTest extends TestCase
{
    private function createFakeInstance(): object
    {
        return new class {
            use ErrorsTrait;
        };
    }

    public function testGetErrors(): void
    {
        $mock = $this->createFakeInstance();

        $this->assertIsArray($mock->getErrors(false));
        $this->assertIsString($mock->getErrors(true));
    }

    public function testGetHtmlErrors(): void
    {
        $mock = $this->createFakeInstance();

        $this->assertIsString($mock->getHtmlErrors());
    }

    public function testHasAndSetError(): void
    {
        $mock = $this->createFakeInstance();

        $this->assertIsBool($mock->hasError());

        $this->assertFalse($mock->hasError());

        $mock->setErrors("some errors");
        $this->assertTrue($mock->hasError());
    }
}
