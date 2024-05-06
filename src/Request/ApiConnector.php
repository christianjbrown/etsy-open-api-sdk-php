<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Request;

use ChristianBrown\JsonApiClient\JsonApiRequestSenderInterface;

final class ApiConnector implements ApiConnectorInterface
{
    private AuthenticationManagerInterface $authenticationManager;
    private JsonApiRequestSenderInterface $jsonApiRequestSender;

    public function __construct(AuthenticationManagerInterface $authenticationManager, JsonApiRequestSenderInterface $jsonApiRequestSender)
    {
        $this->authenticationManager = $authenticationManager;
        $this->jsonApiRequestSender = $jsonApiRequestSender;
    }

    public function get(string $url, array $queryStrings = []): array
    {
        $headers = $this->authenticationManager->getAuthHeaders();
        $data = $this->jsonApiRequestSender->get($url, $queryStrings, $headers);

        return $data;
    }
}
