<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api\Request;

use ChristianBrown\JsonApiClient\BadResponseTransformerInterface;
use Psr\Http\Message\ResponseInterface;

final class BadResponseTransformer implements BadResponseTransformerInterface
{
    private const ERROR_DESCRIPTION_KEY = 'error_description';
    private const FRIENDLY_NAME = 'Etsy\'s API';
    private const MESSAGE_FROM_ERROR_DESCRIPTION = 'Got a %d response from %s: %s';
    private const MESSAGE_GENERIC = 'Got a %d response from %s';

    public function getFriendlyErrorFromBadResponse(ResponseInterface $response): string
    {
        $statusCode = $response->getStatusCode();

        return sprintf(self::MESSAGE_GENERIC, $statusCode, self::FRIENDLY_NAME, $response->getBody());
    }

    public function getFriendlyErrorFromBadResponseJsonData(ResponseInterface $response, array $responseData): string
    {
        $message = $this->getFriendlyErrorFromBadResponse($response);
        if (!empty($responseData[self::ERROR_DESCRIPTION_KEY]) && is_string($responseData[self::ERROR_DESCRIPTION_KEY])) {
            $statusCode = $response->getStatusCode();
            $message = sprintf(self::MESSAGE_FROM_ERROR_DESCRIPTION, $statusCode, self::FRIENDLY_NAME, $responseData[self::ERROR_DESCRIPTION_KEY]);
        }

        return $message;
    }
}
