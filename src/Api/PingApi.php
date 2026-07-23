<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PingInterface;
use ChristianBrown\Etsy\Transformer\PingTransformerInterface;

final class PingApi implements PingApiInterface
{
    private CredentialsInterface $credentials;
    private PingTransformerInterface $pingTransformer;
    private JsonApiRequestSenderInterface $requestSender;

    public function __construct(JsonApiRequestSenderInterface $requestSender, PingTransformerInterface $pingTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->pingTransformer = $pingTransformer;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function ping(): PingInterface
    {
        $data = $this->requestSender->get(self::API_URL, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }

        return $this->pingTransformer->transform($data);
    }
}
