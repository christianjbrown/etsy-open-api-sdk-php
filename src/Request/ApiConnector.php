<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Request;

use ChristianBrown\JsonApiClient\RequestSenderInterface;

final class ApiConnector implements ApiConnectorInterface
{
    private AuthenticationManagerInterface $authenticationManager;
    private RequestSenderInterface $requestSender;

    public function __construct(AuthenticationManagerInterface $authenticationManager, RequestSenderInterface $requestSender)
    {
        $this->authenticationManager = $authenticationManager;
        $this->requestSender = $requestSender;
    }

    public function get(string $url, array $queryStrings = []): array
    {
        $headers = $this->authenticationManager->getAuthHeaders();
        $data = $this->requestSender->get(self::FRIENDLY_NAME, $url, $queryStrings, $headers);

        return $data;
    }
}
