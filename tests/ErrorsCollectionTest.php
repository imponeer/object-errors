<?php

namespace Imponeer\ObjectErrors\Tests;

use Generator;
use Imponeer\ObjectErrors\ErrorsCollection;
use Imponeer\ObjectErrors\ParamsMode;
use Imponeer\ObjectErrors\UnsetErrorException;
use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ErrorsCollectionTest extends TestCase
{
    public function testDefaultConstructorParams(): void
    {
        $instance = new ErrorsCollection();
        $this->assertSame(
            ParamsMode::Mode1,
            $instance->mode,
            message: 'When creating ErrorsCollection instance default mode should be MODE_1_PARAM, but isn\'t'
        );
    }

    #[DataProvider('provideConstructorParams')]
    public function testConstructorParams(ParamsMode $mode): void
    {
        $instance = new ErrorsCollection($mode);
        $this->assertSame(
            $mode,
            $instance->mode,
            message: 'Mode ' . $mode->name . ' is different after creating instance'
        );
    }

    public function testOffsetExists(): void
    {
        $instance = new ErrorsCollection(ParamsMode::Mode2);
        $key = crc32(time());
        $this->assertArrayNotHasKey(
            $key,
            $instance,
            message: 'Random key already exists in array but shouldn\'t'
        );
        $instance->add($key, crc32(time()));
        $this->assertArrayHasKey(
            $key,
            $instance,
            message: 'Random key was not found but it should'
        );
    }

    public function testOffsetGet(): void
    {
        $offset = crc32(time());
        $data = sha1(time());

        $instance = new ErrorsCollection(ParamsMode::Mode2);
        $instance->add($offset, $data);
        $this->assertSame($instance[$offset], $data, message: 'Data is not same #1');
        $this->assertSame($instance->offsetGet($offset), $data, message: 'Data is not same #2');
        $this->assertNotNull($instance[$offset], message: 'Data is not same #3');
        $this->assertNotNull($instance->offsetGet($offset), message: 'Data is not same #4');
    }

    public function testOffsetSet(): void
    {
        $offset = crc32(time());
        $data = sha1(time());

        $instance = new ErrorsCollection(ParamsMode::Mode2);
        $instance->add($offset, $data);

        $ndata = md5(time());
        $instance[$offset] = $ndata;
        $this->assertSame($instance[$offset], $ndata, message: 'Changed data is not same as readed one #1');

        $ndata = soundex($ndata);
        $instance->offsetSet($offset, $ndata);
        $this->assertSame($instance[$offset], $ndata, message: 'Changed data is not same as readed one #2');

        $offset = md5(microtime(true));

        $ndata = metaphone($ndata);
        $instance[$offset] = $ndata;
        $this->assertSame($instance[$offset], $ndata, message: 'Changed data is not same as readed one #3');

        $ndata = soundex($ndata);
        $instance->offsetSet($offset, $ndata);
        $this->assertSame($instance[$offset], $ndata, message: 'Changed data is not same as readed one #4');
    }

    public function testOffsetUnset(): void
    {
        $offset = crc32(time());

        $instance = new ErrorsCollection(ParamsMode::Mode2);

        $this->expectException(UnsetErrorException::class);
        $instance->offsetUnset($offset);
    }

    public function testIsEmpty(): void
    {
        $instance = new ErrorsCollection();
        $this->assertTrue($instance->isEmpty(), message: 'Is not empty after creation');

        $instance->add(crc32(time()));
        $this->assertNotTrue($instance->isEmpty(), message: 'Is still empty after one element was added');
    }

    public function testClear(): void
    {
        $instance = new ErrorsCollection();
        $instance->add(crc32(time()));
        $instance->clear();
        $this->assertEmpty($instance, message: 'Clear() must clear');
    }

    public function testStringConversion(): void
    {
        $instance = new ErrorsCollection();

        $this->assertEmpty(
            (string)$instance,
            message: 'Converted to string empty ErrorsCollection must be empty'
        );
        $this->assertEmpty(
            $instance->getHtml(),
            message: 'Converted to HTML empty ErrorsCollection must be empty'
        );

        $instance->add(crc32(time()));

        $this->assertNotEmpty(
            (string)$instance,
            message: 'Converted to string not empty ErrorsCollection must be not empty'
        );
        $this->assertNotEmpty(
            $instance->getHtml(),
            message: 'Converted to HTML not empty ErrorsCollection must be not empty'
        );

        $this->assertIsString(
            $instance->getHtml(),
            message: 'getHTML must generate strings'
        );
    }

    public function testCount(): void
    {
        $instance = new ErrorsCollection();
        $this->assertCount(0, $instance, message: 'Count is not 0 when collection was just created');

        $instance->add(crc32(time()));
        $this->assertSame(1, $instance->count(), message: 'Count must be 1 after one element was added');

        $this->assertCount(1, $instance, message: 'Count function doesn\'t work');
    }

    public static function provideTestAddData(): Generator
    {
        yield 'mode1' => [
            'mode' => ParamsMode::Mode1,
            'addParams' => [
                md5(time())
            ],
            'expectedKey' => 0
        ];

        yield 'mode2asprefix' => [
            'mode' => ParamsMode::Mode2AsPrefix,
            'addParams' => [
                md5(time())
            ],
            'expectedKey' => 0
        ];

        $key = crc32(time());
        yield 'mode2' => [
            'mode' => ParamsMode::Mode2,
            'addParams' => [
                $key,
                md5(time())
            ],
            'expectedKey' => $key
        ];
    }

    #[DataProvider('provideTestAddData')]
    public function testAdd(ParamsMode $mode, array $addParams, int|string $expectedKey): void
    {
        $instance = new ErrorsCollection($mode);
        call_user_func_array([$instance, 'add'], $addParams);
        $this->assertArrayHasKey($expectedKey, $instance);
    }

    /**
     * @throws JsonException
     */
    public function testSerialization(): void
    {
        $instance = new ErrorsCollection(ParamsMode::Mode2);

        $instance->add(md5(time()), sha1(time()));
        $instance->add(crc32(time()), soundex(time()));

        $serialized = serialize($instance);
        $unserialized = unserialize($serialized);
        $this->assertInstanceOf(
            ErrorsCollection::class,
            $unserialized,
            message: 'Unserialized data is not ErrorsCollection class type but it should be #1'
        );
        $this->assertSame(
            $instance->mode,
            $unserialized->mode,
            message: 'Serialization-unserialization fails #1'
        );
        $this->assertSame(
            $instance->toArray(),
            $unserialized->toArray(),
            message: 'Serialization-unserialization fails #2'
        );

        $this->assertIsArray($instance->toArray(), message: 'toArray doesn\'t makes an array');
        $this->assertIsString($instance->toJson(), message: 'toJSON doesn\'t makes a string');
    }

    public static function provideConstructorParams(): Generator
    {
        foreach (ParamsMode::cases() as $mode) {
            yield $mode->name => [
                'mode' => $mode,
            ];
        }
    }
}
