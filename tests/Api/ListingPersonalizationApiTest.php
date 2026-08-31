<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingPersonalizationApi;
use ChristianBrown\Etsy\Api\ListingPersonalizationApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingPersonalizationInterface;
use ChristianBrown\Etsy\Model\UpdateListingPersonalizationRequestInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingPersonalizationRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingPersonalizationApi::class)]
final class ListingPersonalizationApiTest extends TestCase
{
    private const int LISTING_ID = 7;
    private const int SHOP_ID = 42;

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ListingPersonalizationApiInterface::API_URL_WRITE_SPRINTF, self::SHOP_ID, self::LISTING_ID),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ListingPersonalizationTransformerInterface::class), $apiRequestSender);

        $api->delete(self::LISTING_ID);
    }

    public function testGetReturnsPersonalization(): void
    {
        $personalizationData = ['personalization'];
        $headers = ['x-api-key' => 'key'];
        $personalization = self::createStub(ListingPersonalizationInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingPersonalizationApiInterface::API_URL_ONE_SPRINTF, self::LISTING_ID),
                [],
                $headers,
            )
            ->willReturn($personalizationData);

        $personalizationTransformer = self::createMock(ListingPersonalizationTransformerInterface::class);
        $personalizationTransformer->expects(self::once())->method('transform')
            ->with($personalizationData)
            ->willReturn($personalization);

        $api = $this->buildApi($headers, $requestSender, $personalizationTransformer);

        self::assertSame($personalization, $api->get(self::LISTING_ID));
    }

    public function testGetSkipCacheRefetches(): void
    {
        $personalization = self::createStub(ListingPersonalizationInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['personalization']);

        $personalizationTransformer = self::createStub(ListingPersonalizationTransformerInterface::class);
        $personalizationTransformer->method('transform')->willReturn($personalization);

        $api = $this->buildApi([], $requestSender, $personalizationTransformer);

        self::assertSame($personalization, $api->get(self::LISTING_ID, true));
        self::assertSame($personalization, $api->get(self::LISTING_ID, true));
    }

    public function testGetSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPersonalizationTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingPersonalizationApiInterface::UNEXPECTED_RESPONSE);

        $api->get(self::LISTING_ID, true);
    }

    public function testGetThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPersonalizationTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingPersonalizationApiInterface::UNEXPECTED_RESPONSE);

        $api->get(self::LISTING_ID);
    }

    public function testGetUsesCacheOnSecondCall(): void
    {
        $personalization = self::createStub(ListingPersonalizationInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['personalization']);

        $personalizationTransformer = self::createStub(ListingPersonalizationTransformerInterface::class);
        $personalizationTransformer->method('transform')->willReturn($personalization);

        $api = $this->buildApi([], $requestSender, $personalizationTransformer);

        self::assertSame($personalization, $api->get(self::LISTING_ID));
        self::assertSame($personalization, $api->get(self::LISTING_ID));
    }

    public function testUpdateReturnsPersonalization(): void
    {
        $headers = ['x-api-key' => 'key'];
        $personalizationData = ['personalization'];
        $personalization = self::createStub(ListingPersonalizationInterface::class);
        $serializedBody = ['personalization_questions' => []];

        $request = self::createStub(UpdateListingPersonalizationRequestInterface::class);
        $requestSerializer = self::createMock(UpdateListingPersonalizationRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('post')
            ->with(
                sprintf(ListingPersonalizationApiInterface::API_URL_WRITE_SPRINTF, self::SHOP_ID, self::LISTING_ID),
                [ListingPersonalizationApiInterface::KEY_SUPPORTS_MULTIPLE_PERSONALIZATION_QUESTIONS => 'true'],
                $headers,
                $serializedBody,
            )
            ->willReturn($personalizationData);

        $personalizationTransformer = self::createMock(ListingPersonalizationTransformerInterface::class);
        $personalizationTransformer->expects(self::once())->method('transform')
            ->with($personalizationData)
            ->willReturn($personalization);

        $api = $this->buildApi($headers, $requestSender, $personalizationTransformer, null, $requestSerializer);

        self::assertSame($personalization, $api->update(self::LISTING_ID, $request, true));
    }

    public function testUpdateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('post')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPersonalizationTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingPersonalizationApiInterface::UNEXPECTED_RESPONSE);

        $api->update(self::LISTING_ID, self::createStub(UpdateListingPersonalizationRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingPersonalizationTransformerInterface $personalizationTransformer, ?ApiRequestSenderInterface $apiRequestSender = null, ?UpdateListingPersonalizationRequestSerializerInterface $updateListingPersonalizationRequestSerializer = null): ListingPersonalizationApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingPersonalizationApi(
            $requestSender,
            $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class),
            $personalizationTransformer,
            $updateListingPersonalizationRequestSerializer ?? self::createStub(UpdateListingPersonalizationRequestSerializerInterface::class),
            $credentials,
            self::SHOP_ID,
        );
    }
}
