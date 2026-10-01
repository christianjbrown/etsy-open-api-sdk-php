<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\ApiClient\ReadApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingPersonalizationInterface;
use ChristianBrown\Etsy\Model\UpdateListingPersonalizationRequestInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingPersonalizationRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformerInterface;

use function sprintf;

final class ListingPersonalizationApi implements ListingPersonalizationApiInterface
{
    private ReadApiRequestSenderInterface $apiRequestSender;
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private ListingPersonalizationTransformerInterface $listingPersonalizationTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UpdateListingPersonalizationRequestSerializerInterface $updateListingPersonalizationRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ReadApiRequestSenderInterface $apiRequestSender, ListingPersonalizationTransformerInterface $listingPersonalizationTransformer, UpdateListingPersonalizationRequestSerializerInterface $updateListingPersonalizationRequestSerializer, ResponseCacheInterface $cache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->listingPersonalizationTransformer = $listingPersonalizationTransformer;
        $this->updateListingPersonalizationRequestSerializer = $updateListingPersonalizationRequestSerializer;
        $this->cache = $cache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $listingId): void
    {
        $url = sprintf(self::API_URL_WRITE_SPRINTF, $this->shopId, $listingId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->cache->delete((string) $listingId);
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function get(int $listingId, bool $skipCache = false): ListingPersonalizationInterface
    {
        if (!$skipCache) {
            if ($this->cache->has((string) $listingId)) {
                /**
                 * @var ListingPersonalizationInterface $cached
                 */
                $cached = $this->cache->get((string) $listingId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $listingId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $personalization = $this->listingPersonalizationTransformer->transform($data);
        $this->cache->set((string) $listingId, $personalization);

        return $personalization;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function update(int $listingId, UpdateListingPersonalizationRequestInterface $updateListingPersonalizationRequest, bool $supportsMultiplePersonalizationQuestions = false): ListingPersonalizationInterface
    {
        $url = sprintf(self::API_URL_WRITE_SPRINTF, $this->shopId, $listingId);
        $data = $this->requestSender->post($url, self::buildSupportsMultipleQuestionsQuery($supportsMultiplePersonalizationQuestions), $this->credentials->toHeaders(), $this->updateListingPersonalizationRequestSerializer->serialize($updateListingPersonalizationRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $personalization = $this->listingPersonalizationTransformer->transform($data);
        $this->cache->set((string) $listingId, $personalization);

        return $personalization;
    }

    /**
     * @return array<string, string>
     */
    private static function buildSupportsMultipleQuestionsQuery(bool $supportsMultiplePersonalizationQuestions): array
    {
        if (!$supportsMultiplePersonalizationQuestions) {
            return [];
        }

        return [self::KEY_SUPPORTS_MULTIPLE_PERSONALIZATION_QUESTIONS => 'true'];
    }
}
