<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptTransformerInterface;

use function is_array;
use function sprintf;

final class ShopReceiptApi implements ShopReceiptApiInterface
{
    /**
     * @var array<string, array<int, ReceiptInterface>>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;

    /**
     * @var array<int, ReceiptInterface>
     */
    private array $receiptCache = [];
    private ReceiptsTransformerInterface $receiptsTransformer;
    private ReceiptTransformerInterface $receiptTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ReceiptTransformerInterface $receiptTransformer, ReceiptsTransformerInterface $receiptsTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->receiptTransformer = $receiptTransformer;
        $this->receiptsTransformer = $receiptsTransformer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ReceiptInterface>
     */
    public function getMultiple(int $limit = 100, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%d:%d', $limit, $offset);
        if (!$skipCache) {
            if (isset($this->cache[$cacheKey])) {
                return $this->cache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildQuery($limit, $offset), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $receipts = $this->receiptsTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache[$cacheKey] = $receipts;

        return $receipts;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $receiptId, bool $skipCache = false): ReceiptInterface
    {
        if (!$skipCache) {
            if (isset($this->receiptCache[$receiptId])) {
                return $this->receiptCache[$receiptId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $receiptId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $receipt = $this->receiptTransformer->transform($data);
        $this->receiptCache[$receiptId] = $receipt;

        return $receipt;
    }

    /**
     * @return array<string, string>
     */
    private static function buildQuery(int $limit, int $offset): array
    {
        return [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
    }
}
