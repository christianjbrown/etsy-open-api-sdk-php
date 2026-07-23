<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Exception;

use RuntimeException;

final class UnexpectedResponseException extends RuntimeException implements UnexpectedResponseExceptionInterface
{
}
