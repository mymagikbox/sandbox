<?php
declare(strict_types=1);

namespace PhpLab\Modules\Catalog\Category\Domain\Entity;

use PhpLab\Application\Traits\I18nTrait;

class Status
{
    // use I18nTrait;
    private string $value;
    public const STATUS_UNCONFIRMED = 'disabled';
    public const STATUS_ACTIVE = 'active';

    public const ALL_STATUS_LIST = [self::STATUS_UNCONFIRMED, self::STATUS_ACTIVE];

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
            self::STATUS_UNCONFIRMED    => static::t('user.status.unconfirmed'),
            self::STATUS_ACTIVE         => static::t('user.status.active'),
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