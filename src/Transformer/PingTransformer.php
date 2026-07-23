<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Ping;
use ChristianBrown\Etsy\Model\PingInterface;

use function is_int;
use function sprintf;

final class PingTransformer implements PingTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PingInterface
    {
        if (!isset($data[self::KEY_APPLICATION_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_APPLICATION_ID));
        }
        if (!is_int($data[self::KEY_APPLICATION_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_APPLICATION_ID));
        }

        return new Ping($data[self::KEY_APPLICATION_ID]);
    }
}
