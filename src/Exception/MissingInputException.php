<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Exception;

use InvalidArgumentException;

final class MissingInputException extends InvalidArgumentException implements MissingInputExceptionInterface
{
}
