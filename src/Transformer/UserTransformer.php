<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\User;
use ChristianBrown\Etsy\Model\UserInterface;

use function is_int;
use function is_string;
use function sprintf;

final class UserTransformer implements UserTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): UserInterface
    {
        if (!isset($data[self::KEY_USER_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_USER_ID));
        }
        if (!is_int($data[self::KEY_USER_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_USER_ID));
        }
        $user = new User($data[self::KEY_USER_ID]);

        self::applyFirstName($user, $data);
        self::applyImageUrl75x75($user, $data);
        self::applyLastName($user, $data);
        self::applyPrimaryEmail($user, $data);

        return $user;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFirstName(User $user, array $data): void
    {
        if (empty($data[self::KEY_FIRST_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_FIRST_NAME])) {
            return;
        }
        $user->setFirstName($data[self::KEY_FIRST_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyImageUrl75x75(User $user, array $data): void
    {
        if (empty($data[self::KEY_IMAGE_URL_75X75])) {
            return;
        }
        if (!is_string($data[self::KEY_IMAGE_URL_75X75])) {
            return;
        }
        $user->setImageUrl75x75($data[self::KEY_IMAGE_URL_75X75]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLastName(User $user, array $data): void
    {
        if (empty($data[self::KEY_LAST_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_LAST_NAME])) {
            return;
        }
        $user->setLastName($data[self::KEY_LAST_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPrimaryEmail(User $user, array $data): void
    {
        if (empty($data[self::KEY_PRIMARY_EMAIL])) {
            return;
        }
        if (!is_string($data[self::KEY_PRIMARY_EMAIL])) {
            return;
        }
        $user->setPrimaryEmail($data[self::KEY_PRIMARY_EMAIL]);
    }
}
