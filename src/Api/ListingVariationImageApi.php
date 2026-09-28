<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVariationImageInterface;
use ChristianBrown\Etsy\Model\UpdateVariationImagesRequestInterface;
use ChristianBrown\Etsy\Serializer\UpdateVariationImagesRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingVariationImagesTransformerInterface;

use function is_array;
use function sprintf;

final class ListingVariationImageApi implements ListingVariationImageApiInterface
{
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private ListingVariationImagesTransformerInterface $listingVariationImagesTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UpdateVariationImagesRequestSerializerInterface $updateVariationImagesRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ListingVariationImagesTransformerInterface $listingVariationImagesTransformer, UpdateVariationImagesRequestSerializerInterface $updateVariationImagesRequestSerializer, ResponseCacheInterface $cache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->listingVariationImagesTransformer = $listingVariationImagesTransformer;
        $this->updateVariationImagesRequestSerializer = $updateVariationImagesRequestSerializer;
        $this->cache = $cache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingVariationImageInterface>
     */
    public function getMultiple(int $listingId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->cache->has((string) $listingId)) {
                /**
                 * @var array<int, ListingVariationImageInterface> $cached
                 */
                $cached = $this->cache->get((string) $listingId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId, $listingId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $variationImages = $this->listingVariationImagesTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache->set((string) $listingId, $variationImages);

        return $variationImages;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingVariationImageInterface>
     */
    public function update(int $listingId, UpdateVariationImagesRequestInterface $updateVariationImagesRequest): array
    {
        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId, $listingId);
        $data = $this->requestSender->post($url, [], $this->credentials->toHeaders(), $this->updateVariationImagesRequestSerializer->serialize($updateVariationImagesRequest));

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $variationImages = $this->listingVariationImagesTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache->set((string) $listingId, $variationImages);

        return $variationImages;
    }
}
