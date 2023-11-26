<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\ResultSet;
use ChristianBrown\Etsy\Transformer\ObjectsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ResultSetTransformer;
use ChristianBrown\Etsy\Transformer\ResultSetTransformerInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

use function sprintf;

#[CoversClass(ResultSet::class)]
#[CoversClass(ResultSetTransformer::class)]
final class ResultSetTransformerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $dataResults = [
            ['test-data-1'],
            ['test-data-2'],
        ];
        $data = [
            ResultSetTransformerInterface::KEY_COUNT => 42,
            ResultSetTransformerInterface::KEY_RESULTS => $dataResults,
        ];

        $object1 = $this->createMock(stdClass::class);
        $object2 = $this->createMock(stdClass::class);
        $objects = [
            $object1,
            $object2,
        ];

        $objectsTransformer = $this->createMock(ObjectsTransformerInterface::class);
        $objectsTransformer->method('transform')
            ->with($dataResults)
            ->willReturn($objects);

        $transformer = new ResultSetTransformer();
        $actual = $transformer->transform($data, $objectsTransformer);

        self::assertSame(42, $actual->getTotal());
        self::assertSame(2, $actual->getCount());
        self::assertSame($objects, $actual->getResults());
    }

    /**
     * @throws Exception
     */
    #[TestWith(
        [
            [
                ResultSetTransformerInterface::KEY_RESULTS => [],
            ],
        ]
    )]
    #[TestWith(
        [
            [
                ResultSetTransformerInterface::KEY_COUNT => 'test-invalid-count',
                ResultSetTransformerInterface::KEY_RESULTS => [],
            ],
        ]
    )]
    public function testMissingOrInvalidCount(array $data): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('ResultSet %d missing or not numeric', ResultSetTransformerInterface::KEY_COUNT));

        $objectsTransformer = $this->createMock(ObjectsTransformerInterface::class);

        $transformer = new ResultSetTransformer();
        $transformer->transform($data, $objectsTransformer);
    }

    /**
     * @throws Exception
     */
    #[TestWith(
        [
            [
                ResultSetTransformerInterface::KEY_COUNT => 42,
            ],
        ]
    )]
    #[TestWith(
        [
            [
                ResultSetTransformerInterface::KEY_COUNT => 42,
                ResultSetTransformerInterface::KEY_RESULTS => 'test-invalid-results',
            ],
        ]
    )]
    public function testMissingOrInvalidResults(array $data): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('ResultSet %d missing or not an array', ResultSetTransformerInterface::KEY_RESULTS));

        $objectsTransformer = $this->createMock(ObjectsTransformerInterface::class);

        $transformer = new ResultSetTransformer();
        $transformer->transform($data, $objectsTransformer);
    }
}
