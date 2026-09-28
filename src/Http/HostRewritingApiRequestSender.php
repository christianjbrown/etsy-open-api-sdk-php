<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Http;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\Etsy\Host\EtsyHostInterface;

use function str_replace;

/**
 * The raw-body counterpart to `HostRewritingJsonApiRequestSender`, used by the `DELETE` and
 * multipart-upload methods that go through `ApiRequestSenderInterface` directly. See that class
 * for why the rewrite is safe to apply unconditionally.
 */
final class HostRewritingApiRequestSender implements ApiRequestSenderInterface
{
    private EtsyHostInterface $host;
    private ApiRequestSenderInterface $requestSender;

    public function __construct(ApiRequestSenderInterface $requestSender, EtsyHostInterface $host)
    {
        $this->requestSender = $requestSender;
        $this->host = $host;
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     */
    public function delete(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): string
    {
        return $this->requestSender->delete($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     */
    public function get(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): string
    {
        return $this->requestSender->get($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     */
    public function patch(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?string $requestBody = null): string
    {
        return $this->requestSender->patch($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBody);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     * @param array<string, string> $requestBodyFormData
     */
    public function patchForm(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyFormData = []): string
    {
        return $this->requestSender->patchForm($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBodyFormData);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     */
    public function post(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?string $requestBody = null): string
    {
        return $this->requestSender->post($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBody);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     * @param array<string, string> $requestBodyFormData
     */
    public function postForm(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyFormData = []): string
    {
        return $this->requestSender->postForm($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBodyFormData);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     */
    public function put(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?string $requestBody = null): string
    {
        return $this->requestSender->put($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBody);
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     * @param array<string, string> $requestBodyFormData
     */
    public function putForm(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyFormData = []): string
    {
        return $this->requestSender->putForm($this->rewrite($requestUrl), $requestQueryStrings, $requestHeaders, $requestBodyFormData);
    }

    private function rewrite(string $requestUrl): string
    {
        return str_replace(EtsyHostInterface::PRODUCTION_API_BASE_URL, $this->host->getApiBaseUrl(), $requestUrl);
    }
}
