<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\ListingTranslationRequest;
use ChristianBrown\Etsy\Serializer\ListingTranslationRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingTranslationRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingTranslationRequest::class)]
#[CoversClass(ListingTranslationRequestSerializer::class)]
final class ListingTranslationRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $title = 'test-title';
        $description = 'test-description';
        $tags = ['test-tags-1', 'test-tags-2'];

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeStringList')
            ->willReturnMap(
                [
                    [ListingTranslationRequestSerializerInterface::KEY_TAGS, $tags, ['test-tags' => 'test-tags-value']],
                ]
            );

        $listingTranslationRequest = (new ListingTranslationRequest($title, $description))
            ->setTitle($title)
            ->setDescription($description)
            ->setTags($tags);

        $serializer = new ListingTranslationRequestSerializer($formValueEncoder);

        $expected = [
            ListingTranslationRequestSerializerInterface::KEY_DESCRIPTION => $description,
            'test-tags' => 'test-tags-value',
            ListingTranslationRequestSerializerInterface::KEY_TITLE => $title,
        ];

        self::assertSame($expected, $serializer->serialize($listingTranslationRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $title = 'test-title';
        $description = 'test-description';

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $listingTranslationRequest = new ListingTranslationRequest($title, $description);

        $serializer = new ListingTranslationRequestSerializer($formValueEncoder);

        $expected = [
            ListingTranslationRequestSerializerInterface::KEY_DESCRIPTION => $description,
            ListingTranslationRequestSerializerInterface::KEY_TITLE => $title,
        ];

        self::assertSame($expected, $serializer->serialize($listingTranslationRequest));
    }
}
