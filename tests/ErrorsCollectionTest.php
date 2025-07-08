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
            'When creating ErrorsCollection instance default mode should be MODE_1_PARAM, but isn\'t'
        );
    }

    #[DataProvider('provideConstructorParams')]
    public function testConstructorParams(ParamsMode $mode): void
    {
        $instance = new ErrorsCollection($mode);
        $this->assertSame(
            $mode,
            $instance->mode,
            'Mode ' . $mode->name . ' is different after creating instance'
        );
    }

    public function testOffsetExists(): void
    {
        $instance = new ErrorsCollection(ParamsMode::Mode2);
        $key = (string)crc32((string)time());
        $this->assertArrayNotHasKey(
            $key,
            $instance,
            'Random key already exists in array but shouldn\'t'
        );
        $instance->add($key, (string)crc32((string)time()));
        $this->assertArrayHasKey(
            $key,
            $instance,
            'Random key was not found but it should'
        );
    }

    public function testOffsetGet(): void
    {
        $offset = (string)crc32((string)time());
        $data = sha1((string)time());

        $instance = new ErrorsCollection(ParamsMode::Mode2);
        $instance->add($offset, $data);
        $this->assertSame($instance[$offset], $data, 'Data is not same #1');
        $this->assertSame($instance->offsetGet($offset), $data, 'Data is not same #2');
        $this->assertNotNull($instance[$offset], 'Data is not same #3');
        $this->assertNotNull($instance->offsetGet($offset), 'Data is not same #4');
    }

    public function testOffsetSet(): void
    {
        $offset = (string)crc32((string)time());
        $data = sha1((string)time());

        $instance = new ErrorsCollection(ParamsMode::Mode2);
        $instance->add($offset, $data);

        $ndata = md5((string)time());
        $instance[$offset] = $ndata;
        $this->assertSame($instance[$offset], $ndata, 'Changed data is not same as readed one #1');

        $ndata = soundex((string)$ndata);
        $instance->offsetSet($offset, $ndata);
        $this->assertSame($instance[$offset], $ndata, 'Changed data is not same as readed one #2');

        $offset = md5((string)microtime(true));

        $ndata = metaphone((string)$ndata);
        $instance[$offset] = $ndata;
        $this->assertSame($instance[$offset], $ndata, 'Changed data is not same as readed one #3');

        $ndata = soundex((string)$ndata);
        $instance->offsetSet($offset, $ndata);
        $this->assertSame($instance[$offset], $ndata, 'Changed data is not same as readed one #4');
    }

    public function testOffsetUnset(): void
    {
        $offset = (string)crc32((string)time());

        $instance = new ErrorsCollection(ParamsMode::Mode2);

        $this->expectException(UnsetErrorException::class);
        $instance->offsetUnset($offset);
    }

    public function testIsEmpty(): void
    {
        $instance = new ErrorsCollection();
        $this->assertTrue($instance->isEmpty(), 'Is not empty after creation');

        $instance->add((string)crc32((string)time()));
        $this->assertNotTrue($instance->isEmpty(), 'Is still empty after one element was added');
    }

    public function testClear(): void
    {
        $instance = new ErrorsCollection();
        $instance->add((string)crc32((string)time()));
        $instance->clear();
        $this->assertEmpty($instance, 'Clear() must clear');
    }

    public function testStringConversion(): void
    {
        $instance = new ErrorsCollection();

        $this->assertEmpty(
            (string)$instance,
            'Converted to string empty ErrorsCollection must be empty'
        );
        $this->assertEmpty(
            $instance->getHtml(),
            'Converted to HTML empty ErrorsCollection must be empty'
        );

        $instance->add((string)crc32((string)time()));

        $this->assertNotEmpty(
            (string)$instance,
            'Converted to string not empty ErrorsCollection must be not empty'
        );
        $this->assertNotEmpty(
            $instance->getHtml(),
            'Converted to HTML not empty ErrorsCollection must be not empty'
        );
    }

    public function testCount(): void
    {
        $instance = new ErrorsCollection();
        $this->assertCount(0, $instance, 'Count is not 0 when collection was just created');

        $instance->add((string)crc32((string)time()));
        $this->assertSame(1, $instance->count(), 'Count must be 1 after one element was added');

        $this->assertCount(1, $instance, 'Count function doesn\'t work');
    }

    /**
     * @return Generator<array{mode: ParamsMode, addParams: array<int, int|string>, expectedKey: int|string}>
     */
    public static function provideTestAddData(): Generator
    {
        yield 'mode1' => [
            'mode' => ParamsMode::Mode1,
            'addParams' => [
                md5((string)time())
            ],
            'expectedKey' => 0
        ];

        yield 'mode2asprefix' => [
            'mode' => ParamsMode::Mode2AsPrefix,
            'addParams' => [
                md5((string)time())
            ],
            'expectedKey' => 0
        ];

        $key = (string)crc32((string)time());
        yield 'mode2' => [
            'mode' => ParamsMode::Mode2,
            'addParams' => [
                $key,
                md5((string)time())
            ],
            'expectedKey' => $key
        ];
    }

    /**
     * @param string[] $addParams
     */
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

        $instance->add(md5((string)time()), sha1((string)time()));
        $instance->add((string)crc32((string)time()), soundex((string)time()));

        $serialized = serialize($instance);
        $unserialized = unserialize($serialized);
        $this->assertInstanceOf(
            ErrorsCollection::class,
            $unserialized,
            'Unserialized data is not ErrorsCollection class type but it should be #1'
        );
        $this->assertSame(
            $instance->mode,
            $unserialized->mode,
            'Serialization-unserialization fails #1'
        );
        $this->assertSame(
            $instance->toArray(),
            $unserialized->toArray(),
            'Serialization-unserialization fails #2'
        );
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
