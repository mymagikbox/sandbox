<?php

namespace PhpLab\Domain\Trait;

trait EnumToArray
{
    public static function toArray(): array {
        return array_map(
            fn(self $enum) => $enum->value,
            self::cases()
        );
    }
}