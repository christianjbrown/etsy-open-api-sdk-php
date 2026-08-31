<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Http;

use ChristianBrown\Etsy\Model\MultipartFileInterface;
use Random\RandomException;

use function array_keys;
use function bin2hex;
use function count;
use function random_bytes;
use function sprintf;

final class MultipartFormDataBuilder implements MultipartFormDataBuilderInterface
{
    /**
     * @param string                      $boundary The boundary token separating the parts
     * @param array<string, string>       $fields
     * @param null|MultipartFileInterface $file     The file part, when bytes are being uploaded
     */
    public function build(string $boundary, array $fields, ?MultipartFileInterface $file = null): string
    {
        $body = '';
        $keys = array_keys($fields);
        for ($i = 0, $count = count($keys); $i < $count; ++$i) {
            $body .= sprintf(self::FIELD_PART_SPRINTF, $boundary, $keys[$i], $fields[$keys[$i]]);
        }
        if (null !== $file) {
            $body .= sprintf(self::FILE_PART_SPRINTF, $boundary, $file->getFieldName(), $file->getFileName(), $file->getContentType(), $file->getContents());
        }

        return $body.sprintf(self::CLOSING_SPRINTF, $boundary);
    }

    /**
     * @throws RandomException
     */
    public function generateBoundary(): string
    {
        return sprintf(self::BOUNDARY_SPRINTF, bin2hex(random_bytes(self::BOUNDARY_BYTES)));
    }

    public function toContentTypeHeaderValue(string $boundary): string
    {
        return sprintf(self::CONTENT_TYPE_SPRINTF, $boundary);
    }
}
