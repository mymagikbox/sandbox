<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Entity;

use PhpLab\Application\Traits\I18nTrait;

class Role
{
    use I18nTrait;
    private string $value;
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER = 'manager';
    public const ALL_ROLES = [self::ROLE_ADMIN, self::ROLE_MANAGER];

    public function getValue(): string
    {
        return $this->value;
    }

    public function getJsonValue(): string
    {
        $list = $this->getOptionList();
        return $list[$this->getValue()] ?? '';
    }

    public function getOptionList(): array
    {
        return [
            self::ROLE_ADMIN    => static::t('user.role.admin'),
            self::ROLE_MANAGER  => static::t('user.role.manager'),
        ];
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public static function create(string $value): static
    {
        $field = new static();
        $field->setValue($value);
        return $field;
    }
}