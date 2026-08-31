<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateListingPersonalizationRequestInterface;

final class UpdateListingPersonalizationRequestSerializer implements UpdateListingPersonalizationRequestSerializerInterface
{
    private PersonalizationQuestionRequestsSerializerInterface $personalizationQuestionRequestsSerializer;

    public function __construct(PersonalizationQuestionRequestsSerializerInterface $personalizationQuestionRequestsSerializer)
    {
        $this->personalizationQuestionRequestsSerializer = $personalizationQuestionRequestsSerializer;
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(UpdateListingPersonalizationRequestInterface $updateListingPersonalizationRequest): array
    {
        $data = [];

        $data = $this->applyPersonalizationQuestions($data, $updateListingPersonalizationRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function applyPersonalizationQuestions(array $data, UpdateListingPersonalizationRequestInterface $updateListingPersonalizationRequest): array
    {
        $data[self::KEY_PERSONALIZATION_QUESTIONS] = $this->personalizationQuestionRequestsSerializer->serialize($updateListingPersonalizationRequest->getPersonalizationQuestions());

        return $data;
    }
}
