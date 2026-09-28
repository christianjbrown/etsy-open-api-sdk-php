<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentInterface;
use ChristianBrown\Etsy\Transformer\PaymentsTransformerInterface;

use function implode;
use function is_array;
use function sprintf;

final class PaymentApi implements PaymentApiInterface
{
    private ResponseCacheInterface $byLedgerEntryIdsCache;
    private ResponseCacheInterface $byPaymentIdsCache;
    private ResponseCacheInterface $byReceiptCache;
    private CredentialsInterface $credentials;
    private PaymentsTransformerInterface $paymentsTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;

    public function __construct(JsonApiRequestSenderInterface $requestSender, PaymentsTransformerInterface $paymentsTransformer, ResponseCacheInterface $byLedgerEntryIdsCache, ResponseCacheInterface $byPaymentIdsCache, ResponseCacheInterface $byReceiptCache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->paymentsTransformer = $paymentsTransformer;
        $this->byLedgerEntryIdsCache = $byLedgerEntryIdsCache;
        $this->byPaymentIdsCache = $byPaymentIdsCache;
        $this->byReceiptCache = $byReceiptCache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @param array<int, int> $ledgerEntryIds
     *
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, PaymentInterface>
     */
    public function getByLedgerEntryIds(array $ledgerEntryIds, bool $skipCache = false): array
    {
        $cacheKey = implode(',', $ledgerEntryIds);
        if (!$skipCache) {
            if ($this->byLedgerEntryIdsCache->has($cacheKey)) {
                /**
                 * @var array<int, PaymentInterface> $cached
                 */
                $cached = $this->byLedgerEntryIdsCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_LEDGER_ENTRY_IDS_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildLedgerEntryIdsQuery($ledgerEntryIds), $this->credentials->toHeaders());

        $payments = $this->handleResults($data);
        $this->byLedgerEntryIdsCache->set($cacheKey, $payments);

        return $payments;
    }

    /**
     * @param array<int, int> $paymentIds
     *
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, PaymentInterface>
     */
    public function getByPaymentIds(array $paymentIds, bool $skipCache = false): array
    {
        $cacheKey = implode(',', $paymentIds);
        if (!$skipCache) {
            if ($this->byPaymentIdsCache->has($cacheKey)) {
                /**
                 * @var array<int, PaymentInterface> $cached
                 */
                $cached = $this->byPaymentIdsCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_PAYMENT_IDS_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildPaymentIdsQuery($paymentIds), $this->credentials->toHeaders());

        $payments = $this->handleResults($data);
        $this->byPaymentIdsCache->set($cacheKey, $payments);

        return $payments;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, PaymentInterface>
     */
    public function getByReceipt(int $receiptId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->byReceiptCache->has((string) $receiptId)) {
                /**
                 * @var array<int, PaymentInterface> $cached
                 */
                $cached = $this->byReceiptCache->get((string) $receiptId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_RECEIPT_SPRINTF, $this->shopId, $receiptId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        $payments = $this->handleResults($data);
        $this->byReceiptCache->set((string) $receiptId, $payments);

        return $payments;
    }

    /**
     * @param array<int, int> $ledgerEntryIds
     *
     * @return array<string, string>
     */
    private static function buildLedgerEntryIdsQuery(array $ledgerEntryIds): array
    {
        return [
            self::KEY_LEDGER_ENTRY_IDS => implode(',', $ledgerEntryIds),
        ];
    }

    /**
     * @param array<int, int> $paymentIds
     *
     * @return array<string, string>
     */
    private static function buildPaymentIdsQuery(array $paymentIds): array
    {
        return [
            self::KEY_PAYMENT_IDS => implode(',', $paymentIds),
        ];
    }

    /**
     * @param mixed[] $data
     *
     * @throws UnexpectedResponseException
     *
     * @return array<int, PaymentInterface>
     */
    private function handleResults(array $data): array
    {
        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }

        return $this->paymentsTransformer->transform($data[self::KEY_RESULTS]);
    }
}
