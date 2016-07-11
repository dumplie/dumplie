<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Infrastructure\Doctrine\ORM\Type\Domain;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;
use Dumplie\CustomerService\Domain\PaymentState;

final class PaymentStateType extends IntegerType
{
    const NAME = 'cs_payment_state';

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
     * @return PaymentState
     */
    public function convertToPhpValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return [];
        }

        switch ($value) {
            case 1:
                return new PaymentState\Unpaid();
            case 2:
                return new PaymentState\Paid();
            case 3:
                return new PaymentState\Rejected();
            default:
                ConversionException::conversionFailed($value, $this->getName());
        }
    }

    /**
     * @param PaymentState $value
     * @param AbstractPlatform $platform
     * @return array|string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return [];
        }

        if (!$value instanceof PaymentState) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        switch (get_class($value)) {
            case PaymentState\Unpaid::class:
                return 1;
            case PaymentState\Paid::class:
                return 2;
            case PaymentState\Rejected::class:
                return 3;
            default:
                throw ConversionException::conversionFailed($value, $this->getName());
        }
    }
}