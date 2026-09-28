<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateListingRequestInterface;

interface UpdateListingRequestSerializerInterface
{
    public const string KEY_DESCRIPTION = 'description';
    public const string KEY_ECGT_AFTER_SALES_SERVICE_INFO = 'ecgt_after_sales_service_info';
    public const string KEY_ECGT_GARAN_BRAND = 'ecgt_garan_brand';
    public const string KEY_ECGT_GARAN_GUARANTEE_DETAILS = 'ecgt_garan_guarantee_details';
    public const string KEY_ECGT_GARAN_MODEL = 'ecgt_garan_model';
    public const string KEY_ECGT_GARAN_YEARS = 'ecgt_garan_years';
    public const string KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS = 'ecgt_other_commercial_guarantee_details';
    public const string KEY_ECGT_SOFTWARE_UPDATE_DETAILS = 'ecgt_software_update_details';
    public const string KEY_FEATURED_RANK = 'featured_rank';
    public const string KEY_IMAGE_IDS = 'image_ids';
    public const string KEY_IS_SUPPLY = 'is_supply';
    public const string KEY_IS_TAXABLE = 'is_taxable';
    public const string KEY_ITEM_DIMENSIONS_UNIT = 'item_dimensions_unit';
    public const string KEY_ITEM_HEIGHT = 'item_height';
    public const string KEY_ITEM_LENGTH = 'item_length';
    public const string KEY_ITEM_WEIGHT = 'item_weight';
    public const string KEY_ITEM_WEIGHT_UNIT = 'item_weight_unit';
    public const string KEY_ITEM_WIDTH = 'item_width';
    public const string KEY_MATERIALS = 'materials';
    public const string KEY_PRODUCTION_PARTNER_IDS = 'production_partner_ids';
    public const string KEY_RETURN_POLICY_ID = 'return_policy_id';
    public const string KEY_SHIPPING_PROFILE_ID = 'shipping_profile_id';
    public const string KEY_SHOP_SECTION_ID = 'shop_section_id';
    public const string KEY_SHOULD_AUTO_RENEW = 'should_auto_renew';
    public const string KEY_STATE = 'state';
    public const string KEY_TAGS = 'tags';
    public const string KEY_TAXONOMY_ID = 'taxonomy_id';
    public const string KEY_TITLE = 'title';
    public const string KEY_TYPE = 'type';
    public const string KEY_WHEN_MADE = 'when_made';
    public const string KEY_WHO_MADE = 'who_made';

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateListingRequestInterface $updateListingRequest): array;
}
