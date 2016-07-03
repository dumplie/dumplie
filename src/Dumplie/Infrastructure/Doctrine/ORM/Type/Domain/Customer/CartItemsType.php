<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\ORM\Type\Domain\Customer;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonArrayType;
use Dumplie\Domain\Customer\CartItem;
use Dumplie\Domain\SharedKernel\Product\SKU;

final class CartItemsType extends JsonArrayType
{
    const NAME = 'cart_items';

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return array
     */
    public function convertToPhpValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return [];
        }

        $data = json_decode($value, true);

        if (!count($data)) {
            return [];
        }

        return array_map(function ($raw) {
            return new CartItem(new SKU($raw['sku']), $raw['quantity']);
        }, $data);
    }

    /**
     * @param CartItem[] $value
     * @param AbstractPlatform $platform
     * @return array|string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return [];
        }

        if (!is_array($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $data = [];

        foreach ($value as $item) {
            if (!$item instanceof CartItem) {
                throw ConversionException::conversionFailed($value, $this->getName());
            }

            $data[] = [
                'sku' => (string) $item->sku(),
                'quantity' => $item->quantity()
            ];
        }

        return json_encode($data);
    }
}