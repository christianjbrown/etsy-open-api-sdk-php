<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserInterface;
use ChristianBrown\Etsy\Transformer\UserTransformerInterface;

use function sprintf;

final class UserApi implements UserApiInterface
{
    private CredentialsInterface $credentials;
    private ?UserInterface $meCache = null;
    private JsonApiRequestSenderInterface $requestSender;

    /**
     * @var array<int, UserInterface>
     */
    private array $userCache = [];
    private UserTransformerInterface $userTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, UserTransformerInterface $userTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->userTransformer = $userTransformer;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getById(int $userId, bool $skipCache = false): UserInterface
    {
        if (!$skipCache) {
            if (isset($this->userCache[$userId])) {
                return $this->userCache[$userId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $userId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $user = $this->userTransformer->transform($data);
        $this->userCache[$userId] = $user;

        return $user;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getMe(bool $skipCache = false): UserInterface
    {
        if (!$skipCache) {
            if (null !== $this->meCache) {
                return $this->meCache;
            }
        }

        $data = $this->requestSender->get(self::API_URL_ME, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $user = $this->userTransformer->transform($data);
        $this->meCache = $user;

        return $user;
    }
}
