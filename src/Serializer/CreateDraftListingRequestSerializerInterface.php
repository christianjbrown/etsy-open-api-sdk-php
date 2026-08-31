<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\CreateDraftListingRequestInterface;

interface CreateDraftListingRequestSerializerInterface
{
    public const string KEY_DESCRIPTION = 'description';
    public const string KEY_IMAGE_IDS = 'image_ids';
    public const string KEY_IS_CUSTOMIZABLE = 'is_customizable';
    public const string KEY_IS_SUPPLY = 'is_supply';
    public const string KEY_IS_TAXABLE = 'is_taxable';
    public const string KEY_ITEM_DIMENSIONS_UNIT = 'item_dimensions_unit';
    public const string KEY_ITEM_HEIGHT = 'item_height';
    public const string KEY_ITEM_LENGTH = 'item_length';
    public const string KEY_ITEM_WEIGHT = 'item_weight';
    public const string KEY_ITEM_WEIGHT_UNIT = 'item_weight_unit';
    public const string KEY_ITEM_WIDTH = 'item_width';
    public const string KEY_MATERIALS = 'materials';
    public const string KEY_PRICE = 'price';
    public const string KEY_PROCESSING_MAX = 'processing_max';
    public const string KEY_PROCESSING_MIN = 'processing_min';
    public const string KEY_PRODUCTION_PARTNER_IDS = 'production_partner_ids';
    public const string KEY_QUANTITY = 'quantity';
    public const string KEY_READINESS_STATE_ID = 'readiness_state_id';
    public const string KEY_RETURN_POLICY_ID = 'return_policy_id';
    public const string KEY_SHIPPING_PROFILE_ID = 'shipping_profile_id';
    public const string KEY_SHOP_SECTION_ID = 'shop_section_id';
    public const string KEY_SHOULD_AUTO_RENEW = 'should_auto_renew';
    public const string KEY_STYLES = 'styles';
    public const string KEY_TAGS = 'tags';
    public const string KEY_TAXONOMY_ID = 'taxonomy_id';
    public const string KEY_TITLE = 'title';
    public const string KEY_TYPE = 'type';
    public const string KEY_WHEN_MADE = 'when_made';
    public const string KEY_WHO_MADE = 'who_made';

    /**
     * @return array<string, string>
     */
    public function serialize(CreateDraftListingRequestInterface $createDraftListingRequest): array;
}
