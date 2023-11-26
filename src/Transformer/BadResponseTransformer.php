<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use Psr\Http\Message\ResponseInterface;

use function sprintf;

final class BadResponseTransformer implements BadResponseTransformerInterface
{
    public function getFriendlyErrorFromBadResponse(ResponseInterface $response): string
    {
        $message = sprintf(self::MESSAGE_GENERIC, $response->getStatusCode(), self::FRIENDLY_NAME, $response->getBody());

        return $message;
    }

    public function getFriendlyErrorFromBadResponseJsonData(ResponseInterface $response, array $responseData): string
    {
        if (empty($responseData[self::ERROR_DESCRIPTION_KEY]) || !is_string($responseData[self::ERROR_DESCRIPTION_KEY])) {
            $message = $this->getFriendlyErrorFromBadResponse($response);
        } else {
            $message = sprintf(self::MESSAGE_FROM_ERROR_DESCRIPTION, $response->getStatusCode(), self::FRIENDLY_NAME, $responseData[self::ERROR_DESCRIPTION_KEY]);
        }

        return $message;
    }
}
