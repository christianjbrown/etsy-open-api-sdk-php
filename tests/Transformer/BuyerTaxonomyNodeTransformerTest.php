<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNode;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodeTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodeTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(BuyerTaxonomyNode::class)]
#[CoversClass(BuyerTaxonomyNodeTransformer::class)]
final class BuyerTaxonomyNodeTransformerTest extends TestCase
{
    public function testSetId(): void
    {
        $node = new BuyerTaxonomyNode(1);

        self::assertSame(2, $node->setId(2)->getId());
    }

    public function testTransform(): void
    {
        $grandchild = [
            BuyerTaxonomyNodeTransformerInterface::KEY_ID => 3,
            BuyerTaxonomyNodeTransformerInterface::KEY_LEVEL => 2,
            BuyerTaxonomyNodeTransformerInterface::KEY_NAME => 'gc',
            BuyerTaxonomyNodeTransformerInterface::KEY_PARENT_ID => 2,
            BuyerTaxonomyNodeTransformerInterface::KEY_FULL_PATH_TAXONOMY_IDS => [1, 2, 3],
            BuyerTaxonomyNodeTransformerInterface::KEY_CHILDREN => [],
        ];
        $child = [
            BuyerTaxonomyNodeTransformerInterface::KEY_ID => 2,
            BuyerTaxonomyNodeTransformerInterface::KEY_LEVEL => 1,
            BuyerTaxonomyNodeTransformerInterface::KEY_NAME => 'child',
            BuyerTaxonomyNodeTransformerInterface::KEY_PARENT_ID => 1,
            BuyerTaxonomyNodeTransformerInterface::KEY_FULL_PATH_TAXONOMY_IDS => [1, 2],
            BuyerTaxonomyNodeTransformerInterface::KEY_CHILDREN => [$grandchild],
        ];
        $data = [
            BuyerTaxonomyNodeTransformerInterface::KEY_ID => 1,
            BuyerTaxonomyNodeTransformerInterface::KEY_LEVEL => 0,
            BuyerTaxonomyNodeTransformerInterface::KEY_NAME => 'root',
            BuyerTaxonomyNodeTransformerInterface::KEY_PARENT_ID => 100,
            BuyerTaxonomyNodeTransformerInterface::KEY_FULL_PATH_TAXONOMY_IDS => [1],
            BuyerTaxonomyNodeTransformerInterface::KEY_CHILDREN => [$child],
        ];

        $transformer = new BuyerTaxonomyNodeTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(1, $actual->getId());
        self::assertSame(0, $actual->getLevel());
        self::assertSame('root', $actual->getName());
        self::assertSame(100, $actual->getParentId());
        self::assertSame([1], $actual->getFullPathTaxonomyIds());

        $children = $actual->getChildren();
        self::assertCount(1, $children);
        $childNode = $children[0];
        self::assertSame(2, $childNode->getId());
        self::assertSame(1, $childNode->getLevel());
        self::assertSame('child', $childNode->getName());
        self::assertSame(1, $childNode->getParentId());
        self::assertSame([1, 2], $childNode->getFullPathTaxonomyIds());

        $grandchildren = $childNode->getChildren();
        self::assertCount(1, $grandchildren);
        $grandchildNode = $grandchildren[0];
        self::assertSame(3, $grandchildNode->getId());
        self::assertSame(2, $grandchildNode->getLevel());
        self::assertSame('gc', $grandchildNode->getName());
        self::assertSame(2, $grandchildNode->getParentId());
        self::assertSame([1, 2, 3], $grandchildNode->getFullPathTaxonomyIds());
        self::assertSame([], $grandchildNode->getChildren());
    }

    /**
     * @param array<string, mixed>                      $data
     * @param Closure(BuyerTaxonomyNodeInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new BuyerTaxonomyNodeTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(BuyerTaxonomyNodeInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = BuyerTaxonomyNodeTransformerInterface::KEY_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (BuyerTaxonomyNodeInterface $m): void {
                self::assertNull($m->getLevel());
                self::assertNull($m->getName());
                self::assertNull($m->getParentId());
                self::assertSame([], $m->getFullPathTaxonomyIds());
                self::assertSame([], $m->getChildren());
            },
        ];

        yield 'levelWrongType' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_LEVEL => 'x'], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertNull($m->getLevel());
        }];
        yield 'nameWrongType' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_NAME => 42], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertNull($m->getName());
        }];
        yield 'parentIdWrongType' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_PARENT_ID => 'x'], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertNull($m->getParentId());
        }];
        yield 'fullPathNotArray' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_FULL_PATH_TAXONOMY_IDS => 'x'], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertSame([], $m->getFullPathTaxonomyIds());
        }];
        yield 'fullPathNonIntElement' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_FULL_PATH_TAXONOMY_IDS => [1, 'x', 2]], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertSame([1, 2], $m->getFullPathTaxonomyIds());
        }];
        yield 'fullPathEmpty' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_FULL_PATH_TAXONOMY_IDS => []], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertSame([], $m->getFullPathTaxonomyIds());
        }];
        yield 'fullPathAllInvalid' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_FULL_PATH_TAXONOMY_IDS => ['a', 'b']], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertSame([], $m->getFullPathTaxonomyIds());
        }];
        yield 'childrenNotArray' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_CHILDREN => 5], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertSame([], $m->getChildren());
        }];
        yield 'childrenEmpty' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_CHILDREN => []], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertSame([], $m->getChildren());
        }];
        yield 'childrenNonArrayElement' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_CHILDREN => ['x']], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertSame([], $m->getChildren());
        }];
        yield 'childrenMixed' => [[$id => 1, BuyerTaxonomyNodeTransformerInterface::KEY_CHILDREN => [[BuyerTaxonomyNodeTransformerInterface::KEY_ID => 2], 'x']], static function (BuyerTaxonomyNodeInterface $m): void {
            self::assertCount(1, $m->getChildren());
            self::assertSame(2, $m->getChildren()[0]->getId());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[BuyerTaxonomyNodeTransformerInterface::KEY_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidId(array $data): void
    {
        $transformer = new BuyerTaxonomyNodeTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyNodeTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, BuyerTaxonomyNodeTransformerInterface::KEY_ID));

        $transformer->transform($data);
    }
}
