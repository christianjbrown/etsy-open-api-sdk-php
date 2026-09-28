<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Http;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Host\EtsyHostInterface;

use function str_replace;

/**
 * Every `Api/` class builds its request URL from an `API_URL*` constant rooted at
 * `EtsyHostInterface::PRODUCTION_API_BASE_URL`, so those classes never need to know about a
 * configured host. This decorator rewrites that production prefix to the configured host's base
 * URL before delegating, which is a no-op when the host is still production.
 */
final class HostRewritingJsonApiRequestSender implements JsonApiRequestSenderInterface
{
    private EtsyHostInterface $host;
    private JsonApiRequestSenderInterface $requestSender;

    public function __construct(JsonApiRequestSenderInterface $requestSender, EtsyHostInterface $host)
    {
        $this->requestSender = $requestSender;
        $this->host = $host;
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     *
     * @return array<array-key, mixed>
     */
    public function delete(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): array
    {
        return $this->requestSender->delete($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     *
     * @return array<array-key, mixed>
     */
    public function get(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): array
    {
        return $this->requestSender->get($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders);
    }

    /**
     * @param string                       $requestUrl          The request URL
     * @param array<string, string>        $requestQueryStrings
     * @param array<string, string>        $requestHeaders
     * @param null|array<array-key, mixed> $requestBodyArray
     *
     * @return array<array-key, mixed>
     */
    public function patch(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?array $requestBodyArray = null): array
    {
        return $this->requestSender->patch($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBodyArray);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     * @param array<string, string> $requestBodyFormData
     *
     * @return array<array-key, mixed>
     */
    public function patchForm(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyFormData = []): array
    {
        return $this->requestSender->patchForm($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBodyFormData);
    }

    /**
     * @param string                       $requestUrl          The request URL
     * @param array<string, string>        $requestQueryStrings
     * @param array<string, string>        $requestHeaders
     * @param null|array<array-key, mixed> $requestBodyArray
     *
     * @return array<array-key, mixed>
     */
    public function post(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?array $requestBodyArray = null): array
    {
        return $this->requestSender->post($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBodyArray);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     * @param array<string, string> $requestBodyFormData
     *
     * @return array<array-key, mixed>
     */
    public function postForm(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyFormData = []): array
    {
        return $this->requestSender->postForm($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBodyFormData);
    }

    /**
     * @param string                       $requestUrl          The request URL
     * @param array<string, string>        $requestQueryStrings
     * @param array<string, string>        $requestHeaders
     * @param null|array<array-key, mixed> $requestBodyArray
     *
     * @return array<array-key, mixed>
     */
    public function put(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?array $requestBodyArray = null): array
    {
        return $this->requestSender->put($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBodyArray);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     * @param array<string, string> $requestBodyFormData
     *
     * @return array<array-key, mixed>
     */
    public function putForm(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyFormData = []): array
    {
        return $this->requestSender->putForm($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBodyFormData);
    }

    private function rewrite(string $requestUrl): string
    {
        return str_replace(EtsyHostInterface::PRODUCTION_API_BASE_URL, $this->host->getApiBaseUrl(), $requestUrl);
    }
}
