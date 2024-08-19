<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Enum;

use PhpLab\Domain\Trait\EnumToArray;

enum UserRole: string
{
    use EnumToArray;
    case ADMIN = 'admin';
    case MANAGER = 'manager';

    public static function getOptionList(): array
    {
        return [
            self::ADMIN->value    => self::t('user.role.admin'),
            self::MANAGER->value  => self::t('user.role.manager'),
        ];
    }

    public static function make(int|string $value): ?self
    {
        return self::tryFrom((string)$value);
    }
}