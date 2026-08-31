<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReceiptPage;
use ChristianBrown\Etsy\Model\ReceiptPageInterface;

use function is_array;
use function is_int;
use function sprintf;

final class ReceiptPageTransformer implements ReceiptPageTransformerInterface
{
    private ReceiptsTransformerInterface $receiptsTransformer;

    public function __construct(ReceiptsTransformerInterface $receiptsTransformer)
    {
        $this->receiptsTransformer = $receiptsTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @throws UnexpectedResponseException
     */
    public function transform(array $data): ReceiptPageInterface
    {
        if (!isset($data[self::KEY_COUNT])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_COUNT));
        }
        if (!is_int($data[self::KEY_COUNT])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_COUNT));
        }
        $page = new ReceiptPage($data[self::KEY_COUNT]);

        $this->applyReceipts($page, $data);

        return $page;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyReceipts(ReceiptPage $page, array $data): void
    {
        if (empty($data[self::KEY_RESULTS])) {
            return;
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            return;
        }
        $page->setReceipts($this->receiptsTransformer->transform($data[self::KEY_RESULTS]));
    }
}
