<?php
namespace Imponeer\Tests\ObjectErrors;

use Imponeer\ObjectErrors\ErrorsCollection;
use Imponeer\ObjectErrors\UnsetErrorException;
use PHPUnit\Framework\TestCase;

class ErrorsCollectionTest extends TestCase {

	public function testConstructorParams() {
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

	public function testImplements() {
		$instance = new ErrorsCollection();
		$this->assertInstanceOf(\ArrayAccess::class, $instance, 'ErrorsCollection must implement ArrayAccess');
		$this->assertInstanceOf(\JsonSerializable::class, $instance, 'ErrorsCollection must implement JsonSerializable');
		$this->assertInstanceOf(\Countable::class, $instance, 'ErrorsCollection must implement Countable');
		$this->assertInstanceOf(\Serializable::class, $instance, 'ErrorsCollection must implement Serializable');
	}

	public function testOffsetExists() {
		$instance = new ErrorsCollection(ErrorsCollection::MODE_2_PARAMS);
		$key = crc32(time());
		$this->assertArrayNotHasKey(
			$key,
			$instance,
			'Random key already exists in array but shouldn\'t'
		);
		$instance->add($key, crc32(time()));
		$this->assertArrayHasKey(
			$key,
			$instance,
			'Random key was not found but it should'
		);
	}

	public function testOffsetGet() {
		$offset = crc32(time());
		$data = sha1(time());

		$instance = new ErrorsCollection(ErrorsCollection::MODE_2_PARAMS);
		$instance->add($offset, $data);
		$this->assertSame($instance[$offset], $data, 'Data is not same #1');
		$this->assertSame($instance->offsetGet($offset), $data, 'Data is not same #2');
		$this->assertNotSame($instance[$offset], null, 'Data is not same #3');
		$this->assertNotSame($instance->offsetGet($offset), null, 'Data is not same #4');
	}

	public function testOffsetSet() {
		$offset = crc32(time());
		$data = sha1(time());

		$instance = new ErrorsCollection(ErrorsCollection::MODE_2_PARAMS);
		$instance->add($offset, $data);

		$ndata = md5(time());
		$instance[$offset] = $ndata;
		$this->assertSame($instance[$offset], $ndata, 'Changed data is not same as readed one #1');

		$ndata = soundex($ndata);
		$instance->offsetSet($offset, $ndata);
		$this->assertSame($instance[$offset], $ndata, 'Changed data is not same as readed one #2');

		$offset = md5(microtime(true));

		$ndata = metaphone($ndata);
		$instance[$offset] = $ndata;
		$this->assertSame($instance[$offset], $ndata, 'Changed data is not same as readed one #3');

		$ndata = soundex($ndata);
		$instance->offsetSet($offset, $ndata);
		$this->assertSame($instance[$offset], $ndata, 'Changed data is not same as readed one #4');
	}

	public function testOffsetUnset() {
		$offset = crc32(time());

		$instance = new ErrorsCollection(ErrorsCollection::MODE_2_PARAMS);

		$this->expectException(UnsetErrorException::class);
		$instance->offsetUnset($offset);
		unset($instance[$offset]);

		$this->expectException(null);
		$instance->add($offset, crc32(time()));

		$this->expectException(UnsetErrorException::class);
		$instance->offsetUnset($offset);
		unset($instance[$offset]);

		$this->expectException(null);
	}

	public function testIsEmpty() {
		$instance = new ErrorsCollection();
		$this->assertTrue($instance->isEmpty(), 'Is not empty after creation');

		$instance->add(crc32(time()));
		$this->assertNotTrue($instance->isEmpty(), 'Is still empty after one element was added');
	}

	public function testClear() {
		$instance = new ErrorsCollection();
		$instance->add(crc32(time()));
		$instance->clear();
		$this->assertEmpty($instance, 'Clear() must clear');
	}

	public function testStringConversion() {
		$instance = new ErrorsCollection();

		$this->assertEmpty((string) $instance, 'Converted to string empty ErrorsCollection must be empty');
		$this->assertEmpty($instance->getHtml(), 'Converted to HTML empty ErrorsCollection must be empty');

		$instance->add(crc32(time()));

		$this->assertNotEmpty((string) $instance, 'Converted to string not empty ErrorsCollection must be not empty');
		$this->assertNotEmpty($instance->getHtml(), 'Converted to HTML not empty ErrorsCollection must be not empty');
		$this->assertInternalType('string', $instance->getHtml(), 'getHTML must generate strings');
	}

	public function testCount() {
		$instance = new ErrorsCollection();
		$this->assertSame($instance->count(), 0, 'Count is not 0 when collection was just created');

		$instance->add(crc32(time()));
		$this->assertSame($instance->count(), 1, 'Count must be 1 after one element was added');

		$this->assertCount(1, $instance, 'Count function doesn\'t work');
	}

	public function testAdd() {
		$instance = new ErrorsCollection(ErrorsCollection::MODE_1_PARAM);
		$instance->add(md5(time()));
		$this->assertArrayHasKey(0, $instance, 'With MODE_1_PARAM after adding element first element must be with index 0');

		$instance = new ErrorsCollection(ErrorsCollection::MODE_2_AS_PREFIX);
		$instance->add(md5(time()));
		$this->assertArrayHasKey(0, $instance, 'With MODE_2_AS_PREFIX after adding element first element must be with index 0');

		$instance = new ErrorsCollection(ErrorsCollection::MODE_2_PARAMS);
		$key = crc32(time());
		$instance->add($key, md5(time()));
		$this->assertArrayHasKey($key, $instance, 'With MODE_2_PARAMS after adding element first element must be with index same as added key');
	}

	public function testSerialization() {
		$instance = new ErrorsCollection();

		$instance->mode = ErrorsCollection::MODE_2_PARAMS;
		$instance->add(md5(time()), sha1(time()));
		$instance->add(crc32(time()), soundex(time()));

		$serialized = serialize($instance);
		$unserialized = unserialize($serialized);
		$this->assertInstanceOf(ErrorsCollection::class, $unserialized, 'Unserialized data is not ErrorsCollection class type but it should be #1');
		$this->assertSame($instance->mode, $unserialized->mode, 'Serialization-unserialization fails #1');
		$this->assertSame($instance->toArray(), $unserialized->toArray(), 'Serialization-unserialization fails #2');

		$this->assertInternalType('array', $instance->toArray(), 'toArray doesn\'t makes an array');
		$this->assertInternalType('string', $instance->toJson(), 'toJSON doesn\'t makes a string');
	}

}