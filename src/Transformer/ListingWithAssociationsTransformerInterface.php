<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

interface ListingWithAssociationsTransformerInterface
{
    public const string KEY_BUYER_PRICE = 'buyer_price';
    public const string KEY_CONVERTED_PRICE = 'converted_price';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_CREATION_TIMESTAMP = 'creation_timestamp';
    public const string KEY_DESCRIPTION = 'description';
    public const string KEY_ENDING_TIMESTAMP = 'ending_timestamp';
    public const string KEY_FEATURED_RANK = 'featured_rank';
    public const string KEY_FILE_DATA = 'file_data';
    public const string KEY_HAS_VARIATIONS = 'has_variations';
    public const string KEY_IMAGES = 'images';
    public const string KEY_INVENTORY = 'inventory';
    public const string KEY_IS_CUSTOMIZABLE = 'is_customizable';
    public const string KEY_IS_PERSONALIZABLE = 'is_personalizable';
    public const string KEY_IS_PRIVATE = 'is_private';
    public const string KEY_IS_SUPPLY = 'is_supply';
    public const string KEY_IS_TAXABLE = 'is_taxable';
    public const string KEY_ITEM_DIMENSIONS_UNIT = 'item_dimensions_unit';
    public const string KEY_ITEM_HEIGHT = 'item_height';
    public const string KEY_ITEM_LENGTH = 'item_length';
    public const string KEY_ITEM_WEIGHT = 'item_weight';
    public const string KEY_ITEM_WEIGHT_UNIT = 'item_weight_unit';
    public const string KEY_ITEM_WIDTH = 'item_width';
    public const string KEY_LANGUAGE = 'language';
    public const string KEY_LAST_MODIFIED_TIMESTAMP = 'last_modified_timestamp';
    public const string KEY_LISTING_ID = 'listing_id';
    public const string KEY_LISTING_TYPE = 'listing_type';
    public const string KEY_MATERIALS = 'materials';
    public const string KEY_NON_TAXABLE = 'non_taxable';
    public const string KEY_NUM_FAVORERS = 'num_favorers';
    public const string KEY_ORIGINAL_CREATION_TIMESTAMP = 'original_creation_timestamp';
    public const string KEY_PERSONALIZATION = 'personalization';
    public const string KEY_PRICE = 'price';
    public const string KEY_PROCESSING_MAX = 'processing_max';
    public const string KEY_PROCESSING_MIN = 'processing_min';
    public const string KEY_PRODUCTION_PARTNERS = 'production_partners';
    public const string KEY_QUANTITY = 'quantity';
    public const string KEY_READINESS_STATE_ID = 'readiness_state_id';
    public const string KEY_RETURN_POLICY_ID = 'return_policy_id';
    public const string KEY_RICH_DESCRIPTION = 'rich_description';
    public const string KEY_SHIPPING_PROFILE = 'shipping_profile';
    public const string KEY_SHIPPING_PROFILE_ID = 'shipping_profile_id';
    public const string KEY_SHOP = 'shop';
    public const string KEY_SHOP_ID = 'shop_id';
    public const string KEY_SHOP_SECTION_ID = 'shop_section_id';
    public const string KEY_SHOULD_AUTO_RENEW = 'should_auto_renew';
    public const string KEY_SKUS = 'skus';
    public const string KEY_STATE = 'state';
    public const string KEY_STATE_TIMESTAMP = 'state_timestamp';
    public const string KEY_STYLE = 'style';
    public const string KEY_SUGGESTED_TITLE = 'suggested_title';
    public const string KEY_TAGS = 'tags';
    public const string KEY_TAXONOMY_ID = 'taxonomy_id';
    public const string KEY_TITLE = 'title';
    public const string KEY_TRANSLATIONS = 'translations';
    public const string KEY_UPDATED_TIMESTAMP = 'updated_timestamp';
    public const string KEY_URL = 'url';
    public const string KEY_USER = 'user';
    public const string KEY_USER_ID = 'user_id';
    public const string KEY_VIDEOS = 'videos';
    public const string KEY_VIEWS = 'views';
    public const string KEY_WHEN_MADE = 'when_made';
    public const string KEY_WHO_MADE = 'who_made';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingWithAssociationsInterface;
}
