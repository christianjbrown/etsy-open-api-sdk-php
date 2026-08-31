<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Http;

use ChristianBrown\Etsy\Model\MultipartFileInterface;
use Random\RandomException;

interface MultipartFormDataBuilderInterface
{
    public const int BOUNDARY_BYTES = 16;
    public const string BOUNDARY_SPRINTF = 'ChristianBrownEtsy%s';
    public const string CLOSING_SPRINTF = "--%s--\r\n";
    public const string CONTENT_TYPE_SPRINTF = 'multipart/form-data; boundary=%s';
    public const string FIELD_PART_SPRINTF = "--%s\r\nContent-Disposition: form-data; name=\"%s\"\r\n\r\n%s\r\n";
    public const string FILE_PART_SPRINTF = "--%s\r\nContent-Disposition: form-data; name=\"%s\"; filename=\"%s\"\r\nContent-Type: %s\r\n\r\n%s\r\n";

    /**
     * Renders a `multipart/form-data` body: a part per scalar field, then the file part when one is
     * given. Etsy's three upload endpoints all accept the file as optional, because passing the id of
     * an already-uploaded asset instead re-associates it with the listing.
     *
     * @param string                      $boundary The boundary token separating the parts
     * @param array<string, string>       $fields
     * @param null|MultipartFileInterface $file     The file part, when bytes are being uploaded
     */
    public function build(string $boundary, array $fields, ?MultipartFileInterface $file = null): string;

    /**
     * A fresh, random boundary token. Generated per request so it cannot collide with the bytes of
     * the file being uploaded.
     *
     * @throws RandomException
     */
    public function generateBoundary(): string;

    public function toContentTypeHeaderValue(string $boundary): string;
}
