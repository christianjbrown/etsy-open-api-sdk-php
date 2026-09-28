<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionsTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionsTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ListingPersonalizationTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_OPTION_TRANSFORMER, PersonalizationQuestionOptionTransformer::class);
        $container->register(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_OPTIONS_TRANSFORMER, PersonalizationQuestionOptionsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_OPTION_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_TRANSFORMER, PersonalizationQuestionTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_OPTIONS_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_PERSONALIZATION_QUESTIONS_TRANSFORMER, PersonalizationQuestionsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_PERSONALIZATION_TRANSFORMER, ListingPersonalizationTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PERSONALIZATION_QUESTIONS_TRANSFORMER),
                ]
            );
    }
}
