<?php

namespace Demo\App\Catalog\Infra\Doctrine;

use Demo\App\Catalog\Domain\SizeScaleStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class SizeScaleStatusType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform) {
        return $platform->getVarcharTypeDeclarationSQL($column);
    }
    
    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) {
        return SizeScaleStatus::fromString($value);
    }

    public function getName() {
        return 'size_scale_status';
    }
}
