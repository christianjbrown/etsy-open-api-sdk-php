<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api\Request;

use Psr\Http\Message\ResponseInterface;

final class BadResponseTransformer implements BadResponseTransformerInterface
{
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
