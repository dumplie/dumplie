<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\Doctrine\ORM\Type\Domain;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonArrayType;
use Dumplie\Customer\Domain\OrderItem;
use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;

final class OrderItemsType extends JsonArrayType
{
    const NAME = 'customer_order_items';

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
            return new OrderItem(
                new SKU($raw['sku']),
                new Price($raw['price']['amount'], $raw['price']['currency'], $raw['price']['precision']),
                $raw['quantity']);
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
            if (!$item instanceof OrderItem) {
                throw ConversionException::conversionFailed($value, $this->getName());
            }

            $data[] = [
                'sku' => (string) $item->sku(),
                'price' => [
                    'amount' => $item->price()->amount(),
                    'currency' => $item->price()->currency(),
                    'precision' => $item->price()->precision()
                ],
                'quantity' => $item->quantity(),
            ];
        }

        return json_encode($data);
    }
}