<?php
namespace Imponeer\Tests\ObjectErrors;

use Imponeer\ObjectErrors\ErrorsTrait;
use Mockery;
use PHPUnit\Framework\TestCase;

class ErrorsTraitTest extends TestCase
{

	public function testGetErrors()
	{
		$mock = Mockery::mock(ErrorsTrait::class)->makePartial();
		$mock->shouldReceive('getErrors')->withArgs([false])->once()->andReturnUsing(
			function ($ret) {
				$this->assertInternalType('array', $ret, 'getErrors without param must return array');
			}
		);
		$mock->shouldReceive('getErrors')->withArgs([true])->once()->andReturnUsing(
			function ($ret) {
				$this->assertInternalType('string', $ret, 'getErrors without param must return string');
			}
		);
	}

	public function testGetHtmlErrors()
	{
		$mock = Mockery::mock(ErrorsTrait::class)->makePartial();
		$mock->shouldReceive('getHtmlErrors')->once()->andReturnUsing(
			function ($ret) {
				$this->assertInternalType('string', $ret, 'getErrors without param must return array');
			}
		);
	}

	public function testHasAndSetError()
	{
		$mock = Mockery::mock(ErrorsTrait::class)->makePartial();
		$mock->shouldReceive('hasError')->once()->andReturn(0);
		$mock->shouldReceive('setErrors')
			->withArgs([md5(time())])
			->getMock()
			->shouldReceive('hasError')
			->andReturn(1);
	}

}