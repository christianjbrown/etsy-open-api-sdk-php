<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingFileInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ListingFilesTransformer implements ListingFilesTransformerInterface
{
    private ListingFileTransformerInterface $listingFileTransformer;

    public function __construct(ListingFileTransformerInterface $listingFileTransformer)
    {
        $this->listingFileTransformer = $listingFileTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingFileInterface>
     */
    public function transform(array $data): array
    {
        $listingFiles = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $listingFileData = $values[$i];
            if (!is_array($listingFileData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $listingFiles[] = $this->listingFileTransformer->transform($listingFileData);
        }

        return $listingFiles;
    }
}
