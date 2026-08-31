<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionRequestInterface;

use function array_values;
use function count;

final class PersonalizationQuestionRequestsSerializer implements PersonalizationQuestionRequestsSerializerInterface
{
    private PersonalizationQuestionRequestSerializerInterface $personalizationQuestionRequestSerializer;

    public function __construct(PersonalizationQuestionRequestSerializerInterface $personalizationQuestionRequestSerializer)
    {
        $this->personalizationQuestionRequestSerializer = $personalizationQuestionRequestSerializer;
    }

    /**
     * @param array<int, PersonalizationQuestionRequestInterface> $personalizationQuestionRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $personalizationQuestionRequests): array
    {
        $data = [];
        $values = array_values($personalizationQuestionRequests);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $data[] = $this->personalizationQuestionRequestSerializer->serialize($values[$i]);
        }

        return $data;
    }
}
