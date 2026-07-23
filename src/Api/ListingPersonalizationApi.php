<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingPersonalizationInterface;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformerInterface;

use function sprintf;

final class ListingPersonalizationApi implements ListingPersonalizationApiInterface
{
    /**
     * @var array<int, ListingPersonalizationInterface>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;
    private ListingPersonalizationTransformerInterface $listingPersonalizationTransformer;
    private JsonApiRequestSenderInterface $requestSender;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ListingPersonalizationTransformerInterface $listingPersonalizationTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->listingPersonalizationTransformer = $listingPersonalizationTransformer;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function get(int $listingId, bool $skipCache = false): ListingPersonalizationInterface
    {
        if (!$skipCache) {
            if (isset($this->cache[$listingId])) {
                return $this->cache[$listingId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $listingId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $personalization = $this->listingPersonalizationTransformer->transform($data);
        $this->cache[$listingId] = $personalization;

        return $personalization;
    }
}
