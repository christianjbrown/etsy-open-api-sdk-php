<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopReturnPolicyApi;
use ChristianBrown\Etsy\Api\ShopReturnPolicyApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPoliciesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPolicyTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReturnPolicyApi::class)]
final class ShopReturnPolicyApiTest extends TestCase
{
    private const int SHOP_ID = 42;

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

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ShopReturnPolicyTransformerInterface $shopReturnPolicyTransformer, ShopReturnPoliciesTransformerInterface $shopReturnPoliciesTransformer): ShopReturnPolicyApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopReturnPolicyApi($requestSender, $shopReturnPolicyTransformer, $shopReturnPoliciesTransformer, $credentials, self::SHOP_ID);
    }
}
