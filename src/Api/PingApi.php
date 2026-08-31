<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PingInterface;
use ChristianBrown\Etsy\Model\ScopesInterface;
use ChristianBrown\Etsy\Transformer\PingTransformerInterface;
use ChristianBrown\Etsy\Transformer\ScopesTransformerInterface;

final class PingApi implements PingApiInterface
{
    private CredentialsInterface $credentials;
    private PingTransformerInterface $pingTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private ScopesTransformerInterface $scopesTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, PingTransformerInterface $pingTransformer, ScopesTransformerInterface $scopesTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->pingTransformer = $pingTransformer;
        $this->scopesTransformer = $scopesTransformer;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getScopes(string $token): ScopesInterface
    {
        $data = $this->requestSender->postForm(self::API_URL_SCOPES, [], $this->credentials->toHeaders(), [self::KEY_TOKEN => $token]);

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }

        return $this->scopesTransformer->transform($data);
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
