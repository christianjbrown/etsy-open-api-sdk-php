<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionInterface;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionsTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(PersonalizationQuestionOptionsTransformer::class)]
final class PersonalizationQuestionOptionsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-option-1'], ['test-option-2']];

        $option1 = self::createStub(PersonalizationQuestionOptionInterface::class);
        $option2 = self::createStub(PersonalizationQuestionOptionInterface::class);

        $optionTransformer = self::createStub(PersonalizationQuestionOptionTransformerInterface::class);
        $optionTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-option-1'], $option1],
                    [['test-option-2'], $option2],
                ]
            );

        $transformer = new PersonalizationQuestionOptionsTransformer($optionTransformer);

        self::assertSame([$option1, $option2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $optionTransformer = self::createStub(PersonalizationQuestionOptionTransformerInterface::class);

        $transformer = new PersonalizationQuestionOptionsTransformer($optionTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $optionTransformer = self::createStub(PersonalizationQuestionOptionTransformerInterface::class);

        $transformer = new PersonalizationQuestionOptionsTransformer($optionTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PersonalizationQuestionOptionsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, PersonalizationQuestionOptionsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
