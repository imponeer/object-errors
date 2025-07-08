<?php

namespace Imponeer\ObjectErrors\Tests;

use Imponeer\ObjectErrors\ErrorsTrait;
use Mockery;
use PHPUnit\Framework\TestCase;

class ErrorsTraitTest extends TestCase
{

	/**
	 * Creates mock for ErrorsTrait
	 *
	 * @return Mockery\Mock|ErrorsTrait
	 */
	protected function createTraitMock()
	{
		$mock = Mockery::mock(ErrorsTrait::class)->makePartial();
		$mock->__construct();

		return $mock;
	}

	public function testGetErrors(): void
	{
		$mock = $this->createTraitMock();

		if (method_exists($this, 'assertIsString')) {

			$this->assertIsArray(
				$mock->getErrors(false),
				'getErrors without param must return array'
			);

			$this->assertIsString(
				$mock->getErrors(true),
				'getErrors without param must return string'
			);

		} else {

			$this->assertIsArray(
				$mock->getErrors(false),
				'getErrors without param must return array'
			);

			$this->assertIsString(
				$mock->getErrors(true),
				'getErrors without param must return string'
			);

		}
	}

	public function testGetHtmlErrors(): void
	{
		$mock = $this->createTraitMock();

		$this->assertIsString(
			$mock->getHtmlErrors(),
			'getErrors without param must return array'
		);
	}

	public function testHasAndSetError(): void
	{
		$mock = $this->createTraitMock();

		$this->assertIsBool(
			$mock->hasError(),
			'hasError method should return bool'
		);

		$this->assertFalse(
			$mock->hasError(),
			'When there are no errors hasError should return false'
		);

		$mock->setErrors("some errors");
		$this->assertTrue(
			$mock->hasError(),
			'When there are some errors hasError should return true'
		);
	}

}