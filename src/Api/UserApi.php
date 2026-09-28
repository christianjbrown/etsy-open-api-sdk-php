<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserInterface;
use ChristianBrown\Etsy\Transformer\UserTransformerInterface;

use function sprintf;

final class UserApi implements UserApiInterface
{
    private CredentialsInterface $credentials;
    private ?UserInterface $meCache = null;
    private JsonApiRequestSenderInterface $requestSender;
    private ResponseCacheInterface $userCache;
    private UserTransformerInterface $userTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, UserTransformerInterface $userTransformer, ResponseCacheInterface $userCache, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->userTransformer = $userTransformer;
        $this->userCache = $userCache;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getById(int $userId, bool $skipCache = false): UserInterface
    {
        if (!$skipCache) {
            if ($this->userCache->has((string) $userId)) {
                /**
                 * @var UserInterface $cached
                 */
                $cached = $this->userCache->get((string) $userId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $userId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $user = $this->userTransformer->transform($data);
        $this->userCache->set((string) $userId, $user);

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
