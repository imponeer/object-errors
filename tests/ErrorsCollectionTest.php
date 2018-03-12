<?php
namespace ImpressPHP\Tests\ObjectErrors;

use ImpressPHP\ObjectErrors\ErrorsCollection;
use PHPUnit\Framework\TestCase;

class ErrorsCollectionTest extends TestCase
{

	public function testConstructorParams()
	{
		foreach ([
					 ErrorsCollection::MODE_1_PARAM,
					 ErrorsCollection::MODE_2_AS_PREFIX,
					 ErrorsCollection::MODE_2_PARAMS
				 ] as $mode) {
			$instance = new ErrorsCollection($mode);
			$this->assertSame(
				$mode,
				$instance->mode,
				'Mode ' . $mode . ' is different after creating instance'
			);
		}
		$instance = new ErrorsCollection();
		$this->assertSame(
			ErrorsCollection::MODE_1_PARAM,
			$instance->mode,
			'When creating ErrorsCollection instance default mode should be MODE_1_PARAM, but isn\'t'
		);
	}

	public function testImplements()
	{
		$instance = new ErrorsCollection();
		$this->assertInstanceOf(\ArrayAccess::class, $instance, 'ErrorsCollection must implement ArrayAccess');
		$this->assertInstanceOf(\JsonSerializable::class, $instance, 'ErrorsCollection must implement JsonSerializable');
		$this->assertInstanceOf(\Countable::class, $instance, 'ErrorsCollection must implement Countable');
		$this->assertInstanceOf(\Serializable::class, $instance, 'ErrorsCollection must implement Serializable');
	}

	public function testOffsetExists($offset)
	{
		$instance = new ErrorsCollection();

	}

	public function testOffsetGet($offset)
	{
	}

	public function testOffsetSet()
	{
	}

	public function testOffsetUnset($offset)
	{

	}

	public function testIsEmpty()
	{
		$instance = new ErrorsCollection();
		$this->assertTrue($instance->isEmpty(), 'Is not empty after creation');

		$instance->add(crc32(time()));
		$this->assertNotTrue($instance->isEmpty(), 'Is still empty after one element was added');
	}

	public function testClear()
	{
		$instance = new ErrorsCollection();
		$instance->add(crc32(time()));
		$this->assertEmpty($instance, 'Clear() must clear');
	}

	public function testStringConversion()
	{

	}

	public function testAdd()
	{

	}

	public function testCount()
	{
		$instance = new ErrorsCollection();
		$this->assertSame($instance->count(), 0, 'Count is not 0 when collection was just created');

		$instance->add(crc32(time()));
		$this->assertSame($instance->count(), 1, 'Count must be 1 after one element was added');

		$this->assertCount(1, 'Count function doesn\'t work');
	}

	public function testSerializeUnserialize()
	{

	}

}