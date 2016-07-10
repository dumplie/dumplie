<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\ORM\Type\Domain\CustomerService;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;
use Dumplie\Domain\CustomerService\OrderState;

final class OrderStateType extends IntegerType
{
    const NAME = 'cs_order_state';

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
     * @return OrderState
     */
    public function convertToPhpValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return [];
        }

        switch ($value) {
            case 1:
                return new OrderState\Accepted();
            case 2:
                return new OrderState\Created();
            case 3:
                return new OrderState\Prepared();
            case 4:
                return new OrderState\Refunded();
            case 5:
                return new OrderState\Rejected();
            case 6:
                return new OrderState\Sent();
            default:
                ConversionException::conversionFailed($value, $this->getName());
        }
    }

    /**
     * @param OrderState $value
     * @param AbstractPlatform $platform
     * @return array|string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return [];
        }

        if (!$value instanceof OrderState) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        switch (get_class($value)) {
            case OrderState\Accepted::class:
                return 1;
            case OrderState\Created::class:
                return 2;
            case OrderState\Prepared::class:
                return 3;
            case OrderState\Refunded::class:
                return 4;
            case OrderState\Rejected::class:
                return 5;
            case OrderState\Sent::class:
                return 6;
            default:
                throw ConversionException::conversionFailed($value, $this->getName());
        }
    }
}