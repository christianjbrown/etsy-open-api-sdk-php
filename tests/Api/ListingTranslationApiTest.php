<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingTranslationApi;
use ChristianBrown\Etsy\Api\ListingTranslationApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingTranslationInterface;
use ChristianBrown\Etsy\Transformer\ListingTranslationTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function rawurlencode;
use function sprintf;

#[CoversClass(ListingTranslationApi::class)]
final class ListingTranslationApiTest extends TestCase
{
    private const string LANGUAGE = 'fr-CA';
    private const int LISTING_ID = 7;
    private const int SHOP_ID = 42;

    public function testGetByLanguageReturnsTranslation(): void
    {
        $translationData = ['translation'];
        $headers = ['x-api-key' => 'key'];
        $translation = self::createStub(ListingTranslationInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingTranslationApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, self::LISTING_ID, rawurlencode(self::LANGUAGE)),
                [],
                $headers,
            )
            ->willReturn($translationData);

        $translationTransformer = self::createMock(ListingTranslationTransformerInterface::class);
        $translationTransformer->expects(self::once())->method('transform')
            ->with($translationData)
            ->willReturn($translation);

        $api = $this->buildApi($headers, $requestSender, $translationTransformer);

        self::assertSame($translation, $api->getByLanguage(self::LISTING_ID, self::LANGUAGE));
    }

    public function testGetByLanguageSkipCacheRefetches(): void
    {
        $translation = self::createStub(ListingTranslationInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['translation']);

        $translationTransformer = self::createStub(ListingTranslationTransformerInterface::class);
        $translationTransformer->method('transform')->willReturn($translation);

        $api = $this->buildApi([], $requestSender, $translationTransformer);

        self::assertSame($translation, $api->getByLanguage(self::LISTING_ID, self::LANGUAGE, true));
        self::assertSame($translation, $api->getByLanguage(self::LISTING_ID, self::LANGUAGE, true));
    }

    public function testGetByLanguageSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingTranslationTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingTranslationApiInterface::UNEXPECTED_RESPONSE);

        $api->getByLanguage(self::LISTING_ID, self::LANGUAGE, true);
    }

    public function testGetByLanguageThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingTranslationTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingTranslationApiInterface::UNEXPECTED_RESPONSE);

        $api->getByLanguage(self::LISTING_ID, self::LANGUAGE);
    }

    public function testGetByLanguageUsesCacheOnSecondCall(): void
    {
        $translation = self::createStub(ListingTranslationInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['translation']);

        $translationTransformer = self::createStub(ListingTranslationTransformerInterface::class);
        $translationTransformer->method('transform')->willReturn($translation);

        $api = $this->buildApi([], $requestSender, $translationTransformer);

        self::assertSame($translation, $api->getByLanguage(self::LISTING_ID, self::LANGUAGE));
        self::assertSame($translation, $api->getByLanguage(self::LISTING_ID, self::LANGUAGE));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingTranslationTransformerInterface $translationTransformer): ListingTranslationApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingTranslationApi($requestSender, $translationTransformer, $credentials, self::SHOP_ID);
    }
}
