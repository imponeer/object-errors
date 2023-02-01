<?php
namespace Imponeer\Tests\ObjectErrors;

use Imponeer\ObjectErrors\ErrorsTrait;
use Mockery;
use PHPUnit\Framework\TestCase;

class ErrorsTraitTest extends TestCase
{

	/**
	 * Creates mock for ErrorsTrait
	 *
	 * @return Mockery\Mock|ErrorsTrait
	 *
	 * @noinspection PhpReturnDocTypeMismatchInspection
	 */
	protected function createTraitMock() {
		$mock = Mockery::mock(ErrorsTrait::class)->makePartial();
		$mock->__construct();

		return $mock;
	}

	public function testGetErrors()
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

			$this->assertInternalType(
				'array',
				$mock->getErrors(false),
				'getErrors without param must return array'
			);

			$this->assertInternalType(
				'string',
				$mock->getErrors(true),
				'getErrors without param must return string'
			);

		}
	}

	public function testGetHtmlErrors()
	{
		$mock = $this->createTraitMock();

		if (method_exists($this, 'assertIsString')) {
			$this->assertIsString(
				$mock->getHtmlErrors(),
				'getErrors without param must return array'
			);
		} else {
			$this->assertInternalType(
				'string',
				$mock->getHtmlErrors(),
				'getErrors without param must return array'
			);
		}
	}

	public function testHasAndSetError()
	{
		$mock = $this->createTraitMock();

		if (method_exists($this, 'assertIsBool')) {
			$this->assertIsBool(
				$mock->hasError(),
				'hasError method should return bool'
			);
		} else {
			$this->assertInternalType(
				'bool',
				$mock->hasError(),
				'hasError method should return bool'
			);
		}
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