<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\User;
use ChristianBrown\Etsy\Model\UserInterface;
use ChristianBrown\Etsy\Transformer\UserTransformer;
use ChristianBrown\Etsy\Transformer\UserTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(User::class)]
#[CoversClass(UserTransformer::class)]
final class UserTransformerTest extends TestCase
{
    public function testSetUserId(): void
    {
        $user = new User(1);

        self::assertSame(2, $user->setUserId(2)->getUserId());
    }

    public function testTransform(): void
    {
        $data = [
            UserTransformerInterface::KEY_USER_ID => 9000,
            UserTransformerInterface::KEY_FIRST_NAME => 'v_firstName',
            UserTransformerInterface::KEY_IMAGE_URL_75X75 => 'v_imageUrl75x75',
            UserTransformerInterface::KEY_LAST_NAME => 'v_lastName',
            UserTransformerInterface::KEY_PRIMARY_EMAIL => 'v_primaryEmail',
        ];

        $transformer = new UserTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getUserId());
        self::assertSame('v_firstName', $actual->getFirstName());
        self::assertSame('v_imageUrl75x75', $actual->getImageUrl75x75());
        self::assertSame('v_lastName', $actual->getLastName());
        self::assertSame('v_primaryEmail', $actual->getPrimaryEmail());
    }

    /**
     * @param array<string, mixed>         $data
     * @param Closure(UserInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new UserTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(UserInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = UserTransformerInterface::KEY_USER_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (UserInterface $model): void {
                self::assertNull($model->getFirstName());
            },
        ];

        yield 'firstNameWrongType' => [[$id => 1, UserTransformerInterface::KEY_FIRST_NAME => 42], static function (UserInterface $m): void {
            self::assertNull($m->getFirstName());
        }];
        yield 'imageUrl75x75WrongType' => [[$id => 1, UserTransformerInterface::KEY_IMAGE_URL_75X75 => 42], static function (UserInterface $m): void {
            self::assertNull($m->getImageUrl75x75());
        }];
        yield 'lastNameWrongType' => [[$id => 1, UserTransformerInterface::KEY_LAST_NAME => 42], static function (UserInterface $m): void {
            self::assertNull($m->getLastName());
        }];
        yield 'primaryEmailWrongType' => [[$id => 1, UserTransformerInterface::KEY_PRIMARY_EMAIL => 42], static function (UserInterface $m): void {
            self::assertNull($m->getPrimaryEmail());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[UserTransformerInterface::KEY_USER_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidUserId(array $data): void
    {
        $transformer = new UserTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(UserTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, UserTransformerInterface::KEY_USER_ID));

        $transformer->transform($data);
    }
}
