<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Serializer\CreateDraftListingRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateReceiptShipmentRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateShopReadinessStateDefinitionRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileDestinationRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileUpgradeRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductOfferingRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductOfferingRequestsSerializer;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductPropertyValueRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductPropertyValueRequestsSerializer;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductRequestsSerializer;
use ChristianBrown\Etsy\Serializer\ListingTranslationRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingVariationImageRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingVariationImageRequestsSerializer;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionOptionRequestSerializer;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionOptionRequestsSerializer;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionRequestSerializer;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionRequestsSerializer;
use ChristianBrown\Etsy\Serializer\ReceiptShipmentCustomsItemRequestSerializer;
use ChristianBrown\Etsy\Serializer\ReceiptShipmentCustomsItemRequestsSerializer;
use ChristianBrown\Etsy\Serializer\ShopReturnPolicyRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateListingInventoryRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateListingPersonalizationRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateListingPropertyRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateListingRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopReadinessStateDefinitionRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopReceiptRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileDestinationRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileUpgradeRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateVariationImagesRequestSerializer;
use ChristianBrown\Etsy\Serializer\UploadListingFileRequestSerializer;
use ChristianBrown\Etsy\Serializer\UploadListingImageRequestSerializer;
use ChristianBrown\Etsy\Serializer\UploadListingVideoRequestSerializer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RequestSerializersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_REQUEST_SERIALIZER, ListingInventoryProductOfferingRequestSerializer::class);

        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_PROPERTY_VALUE_REQUEST_SERIALIZER, ListingInventoryProductPropertyValueRequestSerializer::class);

        $container->register(EtsyInterface::SERVICE_LISTING_VARIATION_IMAGE_REQUEST_SERIALIZER, ListingVariationImageRequestSerializer::class);

        $container->register(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_OPTION_REQUEST_SERIALIZER, PersonalizationQuestionOptionRequestSerializer::class);

        $container->register(EtsyInterface::SERVICE_RECEIPT_SHIPMENT_CUSTOMS_ITEM_REQUEST_SERIALIZER, ReceiptShipmentCustomsItemRequestSerializer::class);

        $container->register(EtsyInterface::SERVICE_UPDATE_SHOP_REQUEST_SERIALIZER, UpdateShopRequestSerializer::class);

        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_REQUESTS_SERIALIZER, ListingInventoryProductOfferingRequestsSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_REQUEST_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_PROPERTY_VALUE_REQUESTS_SERIALIZER, ListingInventoryProductPropertyValueRequestsSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_PROPERTY_VALUE_REQUEST_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_VARIATION_IMAGE_REQUESTS_SERIALIZER, ListingVariationImageRequestsSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_VARIATION_IMAGE_REQUEST_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_OPTION_REQUESTS_SERIALIZER, PersonalizationQuestionOptionRequestsSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_OPTION_REQUEST_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_RECEIPT_SHIPMENT_CUSTOMS_ITEM_REQUESTS_SERIALIZER, ReceiptShipmentCustomsItemRequestsSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_RECEIPT_SHIPMENT_CUSTOMS_ITEM_REQUEST_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_REQUEST_SERIALIZER, PersonalizationQuestionRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_OPTION_REQUESTS_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_REQUESTS_SERIALIZER, PersonalizationQuestionRequestsSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_REQUEST_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_REQUEST_SERIALIZER, ListingInventoryProductRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_REQUESTS_SERIALIZER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_PROPERTY_VALUE_REQUESTS_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_REQUESTS_SERIALIZER, ListingInventoryProductRequestsSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_REQUEST_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_CREATE_DRAFT_LISTING_REQUEST_SERIALIZER, CreateDraftListingRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_LISTING_REQUEST_SERIALIZER, UpdateListingRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_LISTING_PROPERTY_REQUEST_SERIALIZER, UpdateListingPropertyRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_TRANSLATION_REQUEST_SERIALIZER, ListingTranslationRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_RETURN_POLICY_REQUEST_SERIALIZER, ShopReturnPolicyRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_CREATE_SHOP_READINESS_STATE_DEFINITION_REQUEST_SERIALIZER, CreateShopReadinessStateDefinitionRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_SHOP_READINESS_STATE_DEFINITION_REQUEST_SERIALIZER, UpdateShopReadinessStateDefinitionRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_SHOP_RECEIPT_REQUEST_SERIALIZER, UpdateShopReceiptRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_CREATE_SHOP_SHIPPING_PROFILE_REQUEST_SERIALIZER, CreateShopShippingProfileRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_CREATE_SHOP_SHIPPING_PROFILE_DESTINATION_REQUEST_SERIALIZER, CreateShopShippingProfileDestinationRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_CREATE_SHOP_SHIPPING_PROFILE_UPGRADE_REQUEST_SERIALIZER, CreateShopShippingProfileUpgradeRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_SHOP_SHIPPING_PROFILE_REQUEST_SERIALIZER, UpdateShopShippingProfileRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_SHOP_SHIPPING_PROFILE_DESTINATION_REQUEST_SERIALIZER, UpdateShopShippingProfileDestinationRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_SHOP_SHIPPING_PROFILE_UPGRADE_REQUEST_SERIALIZER, UpdateShopShippingProfileUpgradeRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPLOAD_LISTING_FILE_REQUEST_SERIALIZER, UploadListingFileRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPLOAD_LISTING_IMAGE_REQUEST_SERIALIZER, UploadListingImageRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPLOAD_LISTING_VIDEO_REQUEST_SERIALIZER, UploadListingVideoRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_LISTING_INVENTORY_REQUEST_SERIALIZER, UpdateListingInventoryRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_REQUESTS_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_LISTING_PERSONALIZATION_REQUEST_SERIALIZER, UpdateListingPersonalizationRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PERSONALIZATION_QUESTION_REQUESTS_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_UPDATE_VARIATION_IMAGES_REQUEST_SERIALIZER, UpdateVariationImagesRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_VARIATION_IMAGE_REQUESTS_SERIALIZER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_CREATE_RECEIPT_SHIPMENT_REQUEST_SERIALIZER, CreateReceiptShipmentRequestSerializer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_RECEIPT_SHIPMENT_CUSTOMS_ITEM_REQUESTS_SERIALIZER),
                ]
            );
    }
}
