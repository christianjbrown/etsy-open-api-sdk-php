<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Request;

use ChristianBrown\ApiClient\Exception\ExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;

final class ApiConnector implements ApiConnectorInterface
{
    private JsonApiRequestSenderInterface $apiRequestSender;
    private AuthenticationManagerInterface $authenticationManager;

    public function __construct(AuthenticationManagerInterface $authenticationManager, JsonApiRequestSenderInterface $apiRequestSender)
    {
        $this->authenticationManager = $authenticationManager;
        $this->apiRequestSender = $apiRequestSender;
    }

    /**
     * @throws ExceptionInterface
     */
    public function get(string $url, array $queryStrings = []): array
    {
        $headers = $this->authenticationManager->getAuthHeaders();
        $data = $this->apiRequestSender->get($url, $queryStrings, $headers);

        return $data;
    }
}
