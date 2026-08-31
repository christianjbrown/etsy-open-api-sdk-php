<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopReturnPolicyApi;
use ChristianBrown\Etsy\Api\ShopReturnPolicyApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;
use ChristianBrown\Etsy\Model\ShopReturnPolicyRequestInterface;
use ChristianBrown\Etsy\Serializer\ShopReturnPolicyRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPoliciesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPolicyTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReturnPolicyApi::class)]
final class ShopReturnPolicyApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testConsolidateReturnsPolicy(): void
    {
        $headers = ['x-api-key' => 'key'];
        $policyData = ['policy-self'];
        $policy = self::createStub(ShopReturnPolicyInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('postForm')
            ->with(
                sprintf(ShopReturnPolicyApiInterface::API_URL_CONSOLIDATE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
                [
                    ShopReturnPolicyApiInterface::KEY_SOURCE_RETURN_POLICY_ID => '5',
                    ShopReturnPolicyApiInterface::KEY_DESTINATION_RETURN_POLICY_ID => '6',
                ],
            )
            ->willReturn($policyData);

        $policyTransformer = self::createMock(ShopReturnPolicyTransformerInterface::class);
        $policyTransformer->expects(self::once())->method('transform')
            ->with($policyData)
            ->willReturn($policy);

        $api = $this->buildApi($headers, $requestSender, $policyTransformer, self::createStub(ShopReturnPoliciesTransformerInterface::class));

        self::assertSame($policy, $api->consolidate(5, 6));
    }

    public function testConsolidateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('postForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReturnPolicyApiInterface::UNEXPECTED_RESPONSE);

        $api->consolidate(5, 6);
    }

    public function testCreateReturnsPolicy(): void
    {
        $headers = ['x-api-key' => 'key'];
        $policyData = ['policy-self'];
        $policy = self::createStub(ShopReturnPolicyInterface::class);
        $serializedBody = ['accepts_returns' => 'true'];

        $request = self::createStub(ShopReturnPolicyRequestInterface::class);
        $requestSerializer = self::createMock(ShopReturnPolicyRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('postForm')
            ->with(
                sprintf(ShopReturnPolicyApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($policyData);

        $policyTransformer = self::createMock(ShopReturnPolicyTransformerInterface::class);
        $policyTransformer->expects(self::once())->method('transform')
            ->with($policyData)
            ->willReturn($policy);

        $api = $this->buildApi($headers, $requestSender, $policyTransformer, self::createStub(ShopReturnPoliciesTransformerInterface::class), null, $requestSerializer);

        self::assertSame($policy, $api->create($request));
    }

    public function testCreateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('postForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReturnPolicyApiInterface::UNEXPECTED_RESPONSE);

        $api->create(self::createStub(ShopReturnPolicyRequestInterface::class));
    }

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ShopReturnPolicyApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class), $apiRequestSender);

        $api->delete(77);
    }

    public function testGetMultipleReturnsPolicies(): void
    {
        $resultsData = [['policy-1'], ['policy-2']];
        $headers = ['x-api-key' => 'key'];
        $policies = [self::createStub(ShopReturnPolicyInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReturnPolicyApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
            )
            ->willReturn([ShopReturnPolicyApiInterface::KEY_RESULTS => $resultsData]);

        $policiesTransformer = self::createMock(ShopReturnPoliciesTransformerInterface::class);
        $policiesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($policies);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), $policiesTransformer);

        self::assertSame($policies, $api->getMultiple());
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $policies = [self::createStub(ShopReturnPolicyInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopReturnPolicyApiInterface::KEY_RESULTS => [['policy-1']]]);

        $policiesTransformer = self::createStub(ShopReturnPoliciesTransformerInterface::class);
        $policiesTransformer->method('transform')->willReturn($policies);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), $policiesTransformer);

        self::assertSame($policies, $api->getMultiple(true));
        self::assertSame($policies, $api->getMultiple(true));
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReturnPolicyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReturnPolicyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReturnPolicyApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReturnPolicyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReturnPolicyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReturnPolicyApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReturnPolicyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReturnPolicyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReturnPolicyApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReturnPolicyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReturnPolicyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReturnPolicyApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $policies = [self::createStub(ShopReturnPolicyInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopReturnPolicyApiInterface::KEY_RESULTS => [['policy-1']]]);

        $policiesTransformer = self::createStub(ShopReturnPoliciesTransformerInterface::class);
        $policiesTransformer->method('transform')->willReturn($policies);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), $policiesTransformer);

        self::assertSame($policies, $api->getMultiple());
        self::assertSame($policies, $api->getMultiple());
    }

    public function testGetOneByIdReturnsPolicy(): void
    {
        $policyData = ['policy-self'];
        $headers = ['x-api-key' => 'key'];
        $policy = self::createStub(ShopReturnPolicyInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReturnPolicyApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
            )
            ->willReturn($policyData);

        $policyTransformer = self::createMock(ShopReturnPolicyTransformerInterface::class);
        $policyTransformer->expects(self::once())->method('transform')
            ->with($policyData)
            ->willReturn($policy);

        $api = $this->buildApi($headers, $requestSender, $policyTransformer, self::createStub(ShopReturnPoliciesTransformerInterface::class));

        self::assertSame($policy, $api->getOneById(77));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $policy = self::createStub(ShopReturnPolicyInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['policy-self']);

        $policyTransformer = self::createStub(ShopReturnPolicyTransformerInterface::class);
        $policyTransformer->method('transform')->willReturn($policy);

        $api = $this->buildApi([], $requestSender, $policyTransformer, self::createStub(ShopReturnPoliciesTransformerInterface::class));

        self::assertSame($policy, $api->getOneById(77, true));
        self::assertSame($policy, $api->getOneById(77, true));
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReturnPolicyApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(77, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReturnPolicyApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(77);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $policy = self::createStub(ShopReturnPolicyInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['policy-self']);

        $policyTransformer = self::createStub(ShopReturnPolicyTransformerInterface::class);
        $policyTransformer->method('transform')->willReturn($policy);

        $api = $this->buildApi([], $requestSender, $policyTransformer, self::createStub(ShopReturnPoliciesTransformerInterface::class));

        self::assertSame($policy, $api->getOneById(77));
        self::assertSame($policy, $api->getOneById(77));
    }

    public function testUpdateReturnsPolicy(): void
    {
        $headers = ['x-api-key' => 'key'];
        $policyData = ['policy-self'];
        $policy = self::createStub(ShopReturnPolicyInterface::class);
        $serializedBody = ['accepts_returns' => 'false'];

        $request = self::createStub(ShopReturnPolicyRequestInterface::class);
        $requestSerializer = self::createMock(ShopReturnPolicyRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('putForm')
            ->with(
                sprintf(ShopReturnPolicyApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($policyData);

        $policyTransformer = self::createMock(ShopReturnPolicyTransformerInterface::class);
        $policyTransformer->expects(self::once())->method('transform')
            ->with($policyData)
            ->willReturn($policy);

        $api = $this->buildApi($headers, $requestSender, $policyTransformer, self::createStub(ShopReturnPoliciesTransformerInterface::class), null, $requestSerializer);

        self::assertSame($policy, $api->update(77, $request));
    }

    public function testUpdateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('putForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReturnPolicyTransformerInterface::class), self::createStub(ShopReturnPoliciesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReturnPolicyApiInterface::UNEXPECTED_RESPONSE);

        $api->update(77, self::createStub(ShopReturnPolicyRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ShopReturnPolicyTransformerInterface $shopReturnPolicyTransformer, ShopReturnPoliciesTransformerInterface $shopReturnPoliciesTransformer, ?ApiRequestSenderInterface $apiRequestSender = null, ?ShopReturnPolicyRequestSerializerInterface $shopReturnPolicyRequestSerializer = null): ShopReturnPolicyApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopReturnPolicyApi(
            $requestSender,
            $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class),
            $shopReturnPolicyTransformer,
            $shopReturnPoliciesTransformer,
            $shopReturnPolicyRequestSerializer ?? self::createStub(ShopReturnPolicyRequestSerializerInterface::class),
            $credentials,
            self::SHOP_ID,
        );
    }
}
