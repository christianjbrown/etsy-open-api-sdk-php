<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingImage;
use ChristianBrown\Etsy\Model\ListingImageInterface;
use ChristianBrown\Etsy\Transformer\ListingImageTransformer;
use ChristianBrown\Etsy\Transformer\ListingImageTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingImage::class)]
#[CoversClass(ListingImageTransformer::class)]
final class ListingImageTransformerTest extends TestCase
{
    public function testSetListingImageId(): void
    {
        $listingImage = new ListingImage(1);

        self::assertSame(2, $listingImage->setListingImageId(2)->getListingImageId());
    }

    public function testTransform(): void
    {
        $data = [
            ListingImageTransformerInterface::KEY_LISTING_IMAGE_ID => 9000,
            ListingImageTransformerInterface::KEY_ALT_TEXT => 'v_altText',
            ListingImageTransformerInterface::KEY_BLUE => 101,
            ListingImageTransformerInterface::KEY_BRIGHTNESS => 102,
            ListingImageTransformerInterface::KEY_CREATED_TIMESTAMP => 103,
            ListingImageTransformerInterface::KEY_CREATION_TSZ => 104,
            ListingImageTransformerInterface::KEY_FULL_HEIGHT => 105,
            ListingImageTransformerInterface::KEY_FULL_WIDTH => 106,
            ListingImageTransformerInterface::KEY_GREEN => 107,
            ListingImageTransformerInterface::KEY_HEX_CODE => 'v_hexCode',
            ListingImageTransformerInterface::KEY_HUE => 108,
            ListingImageTransformerInterface::KEY_IS_BLACK_AND_WHITE => true,
            ListingImageTransformerInterface::KEY_LISTING_ID => 109,
            ListingImageTransformerInterface::KEY_RANK => 110,
            ListingImageTransformerInterface::KEY_RED => 111,
            ListingImageTransformerInterface::KEY_SATURATION => 112,
            ListingImageTransformerInterface::KEY_URL_170X135 => 'v_url170x135',
            ListingImageTransformerInterface::KEY_URL_570XN => 'v_url570xN',
            ListingImageTransformerInterface::KEY_URL_75X75 => 'v_url75x75',
            ListingImageTransformerInterface::KEY_URL_FULLXFULL => 'v_urlFullxfull',
        ];

        $transformer = new ListingImageTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getListingImageId());
        self::assertSame('v_altText', $actual->getAltText());
        self::assertSame(101, $actual->getBlue());
        self::assertSame(102, $actual->getBrightness());
        self::assertSame(103, $actual->getCreatedTimestamp());
        self::assertSame(104, $actual->getCreationTsz());
        self::assertSame(105, $actual->getFullHeight());
        self::assertSame(106, $actual->getFullWidth());
        self::assertSame(107, $actual->getGreen());
        self::assertSame('v_hexCode', $actual->getHexCode());
        self::assertSame(108, $actual->getHue());
        self::assertTrue($actual->getIsBlackAndWhite());
        self::assertSame(109, $actual->getListingId());
        self::assertSame(110, $actual->getRank());
        self::assertSame(111, $actual->getRed());
        self::assertSame(112, $actual->getSaturation());
        self::assertSame('v_url170x135', $actual->getUrl170x135());
        self::assertSame('v_url570xN', $actual->getUrl570xN());
        self::assertSame('v_url75x75', $actual->getUrl75x75());
        self::assertSame('v_urlFullxfull', $actual->getUrlFullxfull());
    }

    /**
     * @param array<string, mixed>                 $data
     * @param Closure(ListingImageInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ListingImageTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingImageInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ListingImageTransformerInterface::KEY_LISTING_IMAGE_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ListingImageInterface $m): void {
                self::assertNull($m->getAltText());
                self::assertNull($m->getBlue());
                self::assertNull($m->getBrightness());
                self::assertNull($m->getCreatedTimestamp());
                self::assertNull($m->getCreationTsz());
                self::assertNull($m->getFullHeight());
                self::assertNull($m->getFullWidth());
                self::assertNull($m->getGreen());
                self::assertNull($m->getHexCode());
                self::assertNull($m->getHue());
                self::assertNull($m->getIsBlackAndWhite());
                self::assertNull($m->getListingId());
                self::assertNull($m->getRank());
                self::assertNull($m->getRed());
                self::assertNull($m->getSaturation());
                self::assertNull($m->getUrl170x135());
                self::assertNull($m->getUrl570xN());
                self::assertNull($m->getUrl75x75());
                self::assertNull($m->getUrlFullxfull());
            },
        ];

        yield 'altTextWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_ALT_TEXT => 42], static function (ListingImageInterface $m): void {
            self::assertNull($m->getAltText());
        }];
        yield 'blueWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_BLUE => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getBlue());
        }];
        yield 'brightnessWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_BRIGHTNESS => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getBrightness());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getCreatedTimestamp());
        }];
        yield 'creationTszWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_CREATION_TSZ => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getCreationTsz());
        }];
        yield 'fullHeightWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_FULL_HEIGHT => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getFullHeight());
        }];
        yield 'fullWidthWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_FULL_WIDTH => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getFullWidth());
        }];
        yield 'greenWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_GREEN => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getGreen());
        }];
        yield 'hexCodeWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_HEX_CODE => 42], static function (ListingImageInterface $m): void {
            self::assertNull($m->getHexCode());
        }];
        yield 'hueWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_HUE => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getHue());
        }];
        yield 'isBlackAndWhiteWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_IS_BLACK_AND_WHITE => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getIsBlackAndWhite());
        }];
        yield 'listingIdWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_LISTING_ID => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getListingId());
        }];
        yield 'rankWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_RANK => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getRank());
        }];
        yield 'redWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_RED => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getRed());
        }];
        yield 'saturationWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_SATURATION => 'x'], static function (ListingImageInterface $m): void {
            self::assertNull($m->getSaturation());
        }];
        yield 'url170x135WrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_URL_170X135 => 42], static function (ListingImageInterface $m): void {
            self::assertNull($m->getUrl170x135());
        }];
        yield 'url570xNWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_URL_570XN => 42], static function (ListingImageInterface $m): void {
            self::assertNull($m->getUrl570xN());
        }];
        yield 'url75x75WrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_URL_75X75 => 42], static function (ListingImageInterface $m): void {
            self::assertNull($m->getUrl75x75());
        }];
        yield 'urlFullxfullWrongType' => [[$id => 1, ListingImageTransformerInterface::KEY_URL_FULLXFULL => 42], static function (ListingImageInterface $m): void {
            self::assertNull($m->getUrlFullxfull());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ListingImageTransformerInterface::KEY_LISTING_IMAGE_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidListingImageId(array $data): void
    {
        $transformer = new ListingImageTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingImageTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ListingImageTransformerInterface::KEY_LISTING_IMAGE_ID));

        $transformer->transform($data);
    }
}
