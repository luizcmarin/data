<?php

declare(strict_types=1);

namespace Yiisoft\Data\Tests\Reader\IterableHandler;

use InvalidArgumentException;
use Yiisoft\Data\Reader\Iterable\FilterHandler\InHandler;
use Yiisoft\Data\Tests\TestCase;

final class InTest extends TestCase
{
    public function matchDataProvider(): array
    {
        return [
            [true, ['value', [44, 45, 46]]],
            [true, ['value', [44, '45', 46]]],
            [false, ['value', [1, 2, 3]]],
        ];
    }

    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch(bool $expected, array $arguments): void
    {
        $processor = new InHandler();

        $item = [
            'id' => 1,
            'value' => 45,
        ];

        $this->assertSame($expected, $processor->match($item, $arguments, []));
    }

    public function invalidCountArgumentsDataProvider(): array
    {
        return [
            'zero' => [[]],
            'one' => [[[]]],
            'three' => [[[], [], []]],
            'four' => [[[], [], [], []]],
        ];
    }

    /**
     * @dataProvider invalidCountArgumentsDataProvider
     */
    public function testMatchFailForInvalidCountArguments($arguments): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$arguments should contain exactly two elements.');

        (new InHandler())->match(['id' => 1], $arguments, []);
    }

    /**
     * @dataProvider invalidStringValueDataProvider
     */
    public function testMatchFailForInvalidFieldValue($field): void
    {
        $type = get_debug_type($field);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The field should be string. The $type is received.");

        (new InHandler())->match(['id' => 1], [$field, [1, 2, 3]], []);
    }
}