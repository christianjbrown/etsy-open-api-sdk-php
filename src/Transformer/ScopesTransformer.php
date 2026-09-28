<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Scopes;
use ChristianBrown\Etsy\Model\ScopesInterface;

use function array_filter;
use function array_values;
use function is_array;
use function is_string;
use function sprintf;

final class ScopesTransformer implements ScopesTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ScopesInterface
    {
        if (!isset($data[self::KEY_SCOPES])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::KEY_SCOPES));
        }
        if (!is_array($data[self::KEY_SCOPES])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::KEY_SCOPES));
        }

        /**
         * @var array<int, string> $values
         */
        $values = array_values(array_filter($data[self::KEY_SCOPES], static fn (mixed $item): bool => is_string($item)));

        return new Scopes($values);
    }
}
