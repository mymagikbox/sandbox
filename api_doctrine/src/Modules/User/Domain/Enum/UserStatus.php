<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Enum;

use PhpLab\Domain\Trait\EnumToArray;

enum UserStatus: string
{
    use EnumToArray;

    case DISABLED = 'disabled';
    case ACTIVE = 'active';
    case DELETED = 'deleted';

    public static function getOptionList(): array
    {
        return [
            self::DISABLED->value    => self::t('user.status.unconfirmed'),
            self::ACTIVE->value      => self::t('user.status.active'),
            self::DELETED->value     => self::t('user.status.deleted'),
        ];
    }
}