<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionRequestInterface;

use function array_values;
use function count;

final class PersonalizationQuestionOptionRequestsSerializer implements PersonalizationQuestionOptionRequestsSerializerInterface
{
    private PersonalizationQuestionOptionRequestSerializerInterface $personalizationQuestionOptionRequestSerializer;

    public function __construct(PersonalizationQuestionOptionRequestSerializerInterface $personalizationQuestionOptionRequestSerializer)
    {
        $this->personalizationQuestionOptionRequestSerializer = $personalizationQuestionOptionRequestSerializer;
    }

    /**
     * @param array<int, PersonalizationQuestionOptionRequestInterface> $personalizationQuestionOptionRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $personalizationQuestionOptionRequests): array
    {
        $data = [];
        $values = array_values($personalizationQuestionOptionRequests);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $data[] = $this->personalizationQuestionOptionRequestSerializer->serialize($values[$i]);
        }

        return $data;
    }
}
