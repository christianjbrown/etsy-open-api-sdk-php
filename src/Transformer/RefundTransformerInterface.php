<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\RefundInterface;

interface RefundTransformerInterface
{
    public const string KEY_AMOUNT = 'amount';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_NOTE_FROM_ISSUER = 'note_from_issuer';
    public const string KEY_REASON = 'reason';
    public const string KEY_STATUS = 'status';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): RefundInterface;
}
