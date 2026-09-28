<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingTranslationInterface;
use ChristianBrown\Etsy\Model\ListingTranslationRequestInterface;
use ChristianBrown\Etsy\Serializer\ListingTranslationRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingTranslationTransformerInterface;

use function rawurlencode;
use function sprintf;

final class ListingTranslationApi implements ListingTranslationApiInterface
{
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private ListingTranslationRequestSerializerInterface $listingTranslationRequestSerializer;
    private ListingTranslationTransformerInterface $listingTranslationTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ListingTranslationTransformerInterface $listingTranslationTransformer, ListingTranslationRequestSerializerInterface $listingTranslationRequestSerializer, ResponseCacheInterface $cache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->listingTranslationTransformer = $listingTranslationTransformer;
        $this->listingTranslationRequestSerializer = $listingTranslationRequestSerializer;
        $this->cache = $cache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function create(int $listingId, string $language, ListingTranslationRequestInterface $listingTranslationRequest): ListingTranslationInterface
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $listingId, rawurlencode($language));
        $data = $this->requestSender->postForm($url, [], $this->credentials->toHeaders(), $this->listingTranslationRequestSerializer->serialize($listingTranslationRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $translation = $this->listingTranslationTransformer->transform($data);
        $this->cache->set(sprintf('%d:%s', $listingId, $language), $translation);

        return $translation;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getByLanguage(int $listingId, string $language, bool $skipCache = false): ListingTranslationInterface
    {
        $cacheKey = sprintf('%d:%s', $listingId, $language);
        if (!$skipCache) {
            if ($this->cache->has($cacheKey)) {
                /**
                 * @var ListingTranslationInterface $cached
                 */
                $cached = $this->cache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $listingId, rawurlencode($language));
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $translation = $this->listingTranslationTransformer->transform($data);
        $this->cache->set($cacheKey, $translation);

        return $translation;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function update(int $listingId, string $language, ListingTranslationRequestInterface $listingTranslationRequest): ListingTranslationInterface
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $listingId, rawurlencode($language));
        $data = $this->requestSender->putForm($url, [], $this->credentials->toHeaders(), $this->listingTranslationRequestSerializer->serialize($listingTranslationRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $translation = $this->listingTranslationTransformer->transform($data);
        $this->cache->set(sprintf('%d:%s', $listingId, $language), $translation);

        return $translation;
    }
}
