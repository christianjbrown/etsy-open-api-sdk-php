<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopSectionApi;
use ChristianBrown\Etsy\Api\ShopSectionApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopSectionInterface;
use ChristianBrown\Etsy\Transformer\ShopSectionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopSectionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopSectionApi::class)]
final class ShopSectionApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testGetMultipleReturnsSections(): void
    {
        $resultsData = [['section-1'], ['section-2']];
        $headers = ['x-api-key' => 'key'];
        $sections = [self::createStub(ShopSectionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopSectionApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
            )
            ->willReturn([ShopSectionApiInterface::KEY_RESULTS => $resultsData]);

        $sectionsTransformer = self::createMock(ShopSectionsTransformerInterface::class);
        $sectionsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($sections);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ShopSectionTransformerInterface::class), $sectionsTransformer);

        self::assertSame($sections, $api->getMultiple());
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $sections = [self::createStub(ShopSectionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopSectionApiInterface::KEY_RESULTS => [['section-1']]]);

        $sectionsTransformer = self::createStub(ShopSectionsTransformerInterface::class);
        $sectionsTransformer->method('transform')->willReturn($sections);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), $sectionsTransformer);

        self::assertSame($sections, $api->getMultiple(true));
        self::assertSame($sections, $api->getMultiple(true));
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopSectionApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), self::createStub(ShopSectionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopSectionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopSectionApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopSectionApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), self::createStub(ShopSectionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopSectionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopSectionApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopSectionApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), self::createStub(ShopSectionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopSectionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopSectionApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopSectionApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), self::createStub(ShopSectionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopSectionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopSectionApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $sections = [self::createStub(ShopSectionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopSectionApiInterface::KEY_RESULTS => [['section-1']]]);

        $sectionsTransformer = self::createStub(ShopSectionsTransformerInterface::class);
        $sectionsTransformer->method('transform')->willReturn($sections);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), $sectionsTransformer);

        self::assertSame($sections, $api->getMultiple());
        self::assertSame($sections, $api->getMultiple());
    }

    public function testGetOneByIdReturnsSection(): void
    {
        $sectionData = ['section-self'];
        $headers = ['x-api-key' => 'key'];
        $section = self::createStub(ShopSectionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopSectionApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
            )
            ->willReturn($sectionData);

        $sectionTransformer = self::createMock(ShopSectionTransformerInterface::class);
        $sectionTransformer->expects(self::once())->method('transform')
            ->with($sectionData)
            ->willReturn($section);

        $api = $this->buildApi($headers, $requestSender, $sectionTransformer, self::createStub(ShopSectionsTransformerInterface::class));

        self::assertSame($section, $api->getOneById(77));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $section = self::createStub(ShopSectionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['section-self']);

        $sectionTransformer = self::createStub(ShopSectionTransformerInterface::class);
        $sectionTransformer->method('transform')->willReturn($section);

        $api = $this->buildApi([], $requestSender, $sectionTransformer, self::createStub(ShopSectionsTransformerInterface::class));

        self::assertSame($section, $api->getOneById(77, true));
        self::assertSame($section, $api->getOneById(77, true));
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), self::createStub(ShopSectionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopSectionApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(77, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), self::createStub(ShopSectionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopSectionApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(77);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $section = self::createStub(ShopSectionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['section-self']);

        $sectionTransformer = self::createStub(ShopSectionTransformerInterface::class);
        $sectionTransformer->method('transform')->willReturn($section);

        $api = $this->buildApi([], $requestSender, $sectionTransformer, self::createStub(ShopSectionsTransformerInterface::class));

        self::assertSame($section, $api->getOneById(77));
        self::assertSame($section, $api->getOneById(77));
    }

    public function testCreateReturnsSection(): void
    {
        $headers = ['x-api-key' => 'key'];
        $sectionData = ['section-self'];
        $section = self::createStub(ShopSectionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('postForm')
            ->with(
                sprintf(ShopSectionApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
                [ShopSectionApiInterface::KEY_TITLE => 'New section'],
            )
            ->willReturn($sectionData);

        $sectionTransformer = self::createMock(ShopSectionTransformerInterface::class);
        $sectionTransformer->expects(self::once())->method('transform')
            ->with($sectionData)
            ->willReturn($section);

        $api = $this->buildApi($headers, $requestSender, $sectionTransformer, self::createStub(ShopSectionsTransformerInterface::class));

        self::assertSame($section, $api->create('New section'));
    }

    public function testCreateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('postForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), self::createStub(ShopSectionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopSectionApiInterface::UNEXPECTED_RESPONSE);

        $api->create('New section');
    }

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ShopSectionApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ShopSectionTransformerInterface::class), self::createStub(ShopSectionsTransformerInterface::class), $apiRequestSender);

        $api->delete(77);
    }

    public function testUpdateReturnsSection(): void
    {
        $headers = ['x-api-key' => 'key'];
        $sectionData = ['section-self'];
        $section = self::createStub(ShopSectionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('putForm')
            ->with(
                sprintf(ShopSectionApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
                [ShopSectionApiInterface::KEY_TITLE => 'Renamed'],
            )
            ->willReturn($sectionData);

        $sectionTransformer = self::createMock(ShopSectionTransformerInterface::class);
        $sectionTransformer->expects(self::once())->method('transform')
            ->with($sectionData)
            ->willReturn($section);

        $api = $this->buildApi($headers, $requestSender, $sectionTransformer, self::createStub(ShopSectionsTransformerInterface::class));

        self::assertSame($section, $api->update(77, 'Renamed'));
    }

    public function testUpdateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('putForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopSectionTransformerInterface::class), self::createStub(ShopSectionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopSectionApiInterface::UNEXPECTED_RESPONSE);

        $api->update(77, 'Renamed');
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ShopSectionTransformerInterface $shopSectionTransformer, ShopSectionsTransformerInterface $shopSectionsTransformer, ?ApiRequestSenderInterface $apiRequestSender = null): ShopSectionApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopSectionApi($requestSender, $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class), $shopSectionTransformer, $shopSectionsTransformer, $credentials, self::SHOP_ID);
    }
}
