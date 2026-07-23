<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingTranslation;
use ChristianBrown\Etsy\Model\ListingTranslationInterface;
use ChristianBrown\Etsy\Transformer\ListingTranslationTransformer;
use ChristianBrown\Etsy\Transformer\ListingTranslationTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingTranslation::class)]
#[CoversClass(ListingTranslationTransformer::class)]
final class ListingTranslationTransformerTest extends TestCase
{
    public function testSetListingId(): void
    {
        $translation = new ListingTranslation(1);

        self::assertSame(2, $translation->setListingId(2)->getListingId());
    }

    public function testTransform(): void
    {
        $data = [
            ListingTranslationTransformerInterface::KEY_LISTING_ID => 9000,
            ListingTranslationTransformerInterface::KEY_LANGUAGE => 'v_language',
            ListingTranslationTransformerInterface::KEY_TITLE => 'v_title',
            ListingTranslationTransformerInterface::KEY_DESCRIPTION => 'v_description',
            ListingTranslationTransformerInterface::KEY_TAGS => ['a_tags', 'b_tags'],
        ];

        $transformer = new ListingTranslationTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getListingId());
        self::assertSame('v_language', $actual->getLanguage());
        self::assertSame('v_title', $actual->getTitle());
        self::assertSame('v_description', $actual->getDescription());
        self::assertSame(['a_tags', 'b_tags'], $actual->getTags());
    }

    /**
     * @param array<string, mixed>                       $data
     * @param Closure(ListingTranslationInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ListingTranslationTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingTranslationInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ListingTranslationTransformerInterface::KEY_LISTING_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ListingTranslationInterface $m): void {
                self::assertNull($m->getLanguage());
                self::assertNull($m->getTitle());
                self::assertNull($m->getDescription());
                self::assertSame([], $m->getTags());
            },
        ];

        yield 'languageWrongType' => [[$id => 1, ListingTranslationTransformerInterface::KEY_LANGUAGE => 42], static function (ListingTranslationInterface $m): void {
            self::assertNull($m->getLanguage());
        }];
        yield 'titleWrongType' => [[$id => 1, ListingTranslationTransformerInterface::KEY_TITLE => 42], static function (ListingTranslationInterface $m): void {
            self::assertNull($m->getTitle());
        }];
        yield 'descriptionWrongType' => [[$id => 1, ListingTranslationTransformerInterface::KEY_DESCRIPTION => 42], static function (ListingTranslationInterface $m): void {
            self::assertNull($m->getDescription());
        }];
        yield 'tagsNonArray' => [[$id => 1, ListingTranslationTransformerInterface::KEY_TAGS => 'x'], static function (ListingTranslationInterface $m): void {
            self::assertSame([], $m->getTags());
        }];
        yield 'tagsNonStringElement' => [[$id => 1, ListingTranslationTransformerInterface::KEY_TAGS => ['ok', 42]], static function (ListingTranslationInterface $m): void {
            self::assertSame(['ok'], $m->getTags());
        }];
        yield 'tagsSingleNonString' => [[$id => 1, ListingTranslationTransformerInterface::KEY_TAGS => [42]], static function (ListingTranslationInterface $m): void {
            self::assertSame([], $m->getTags());
        }];
        yield 'tagsEmpty' => [[$id => 1, ListingTranslationTransformerInterface::KEY_TAGS => []], static function (ListingTranslationInterface $m): void {
            self::assertSame([], $m->getTags());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ListingTranslationTransformerInterface::KEY_LISTING_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidListingId(array $data): void
    {
        $transformer = new ListingTranslationTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingTranslationTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ListingTranslationTransformerInterface::KEY_LISTING_ID));

        $transformer->transform($data);
    }
}
