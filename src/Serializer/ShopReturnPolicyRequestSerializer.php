<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\ShopReturnPolicyRequestInterface;

final class ShopReturnPolicyRequestSerializer implements ShopReturnPolicyRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(ShopReturnPolicyRequestInterface $shopReturnPolicyRequest): array
    {
        $data = [];

        $data = $this->applyAcceptsExchanges($data, $shopReturnPolicyRequest);
        $data = $this->applyAcceptsReturns($data, $shopReturnPolicyRequest);
        $data = $this->applyReturnDeadline($data, $shopReturnPolicyRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyAcceptsExchanges(array $data, ShopReturnPolicyRequestInterface $shopReturnPolicyRequest): array
    {
        $data[self::KEY_ACCEPTS_EXCHANGES] = $this->formValueEncoder->encodeBool($shopReturnPolicyRequest->getAcceptsExchanges());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyAcceptsReturns(array $data, ShopReturnPolicyRequestInterface $shopReturnPolicyRequest): array
    {
        $data[self::KEY_ACCEPTS_RETURNS] = $this->formValueEncoder->encodeBool($shopReturnPolicyRequest->getAcceptsReturns());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyReturnDeadline(array $data, ShopReturnPolicyRequestInterface $shopReturnPolicyRequest): array
    {
        $value = $shopReturnPolicyRequest->getReturnDeadline();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_RETURN_DEADLINE] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }
}
