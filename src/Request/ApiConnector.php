<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Request;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\ExceptionInterface;

final class ApiConnector implements ApiConnectorInterface
{
    private ApiRequestSenderInterface $apiRequestSender;
    private AuthenticationManagerInterface $authenticationManager;

    public function __construct(AuthenticationManagerInterface $authenticationManager, ApiRequestSenderInterface $apiRequestSender)
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
