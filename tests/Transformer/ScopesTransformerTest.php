<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Scopes;
use ChristianBrown\Etsy\Transformer\ScopesTransformer;
use ChristianBrown\Etsy\Transformer\ScopesTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(Scopes::class)]
#[CoversClass(ScopesTransformer::class)]
final class ScopesTransformerTest extends TestCase
{
    public function testTransformSkipsNonStrings(): void
    {
        $transformer = new ScopesTransformer();

        $scopes = $transformer->transform([ScopesTransformerInterface::KEY_SCOPES => ['listings_r', 42]]);

        self::assertSame(['listings_r'], $scopes->getScopes());
    }

    public function testTransformsScopes(): void
    {
        $transformer = new ScopesTransformer();

        $scopes = $transformer->transform([ScopesTransformerInterface::KEY_SCOPES => ['listings_r', 'transactions_r']]);

        self::assertSame(['listings_r', 'transactions_r'], $scopes->getScopes());
        self::assertSame(['shops_w'], $scopes->setScopes(['shops_w'])->getScopes());
    }

    public function testTransformThrowsWhenScopesMissing(): void
    {
        $transformer = new ScopesTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ScopesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ScopesTransformerInterface::KEY_SCOPES));

        $transformer->transform([]);
    }

    public function testTransformThrowsWhenScopesNotArray(): void
    {
        $transformer = new ScopesTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ScopesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ScopesTransformerInterface::KEY_SCOPES));

        $transformer->transform([ScopesTransformerInterface::KEY_SCOPES => 'not-an-array']);
    }
}
