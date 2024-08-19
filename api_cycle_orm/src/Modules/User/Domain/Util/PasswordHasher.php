<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Util;

use PhpLab\Domain\Assert;
use RuntimeException;

final class PasswordHasher
{
    private const DEFAULT_SALT = 'q36H:oGg9&t9';

    private static function getSalt(): string
    {
        return $_ENV['PASSWORD_SALT'] ?? self::DEFAULT_SALT;
    }

    public static function generateHash(string $password, array $options = ['cost' => 12]): string
    {
        Assert::notEmpty($password, 'auth.password.not_blank');

        /** @var false|null|string $hash */
        $hash = password_hash($password . self::getSalt(), PASSWORD_BCRYPT, $options);

        if ($hash === null) {
            throw new RuntimeException("Invalid hash algorithm");
        }

        if ($hash === false) {
            throw new RuntimeException("Unable to generate hash");
        }

        return $hash;
    }

    public static function validate(string $password, string $hash): bool
    {
        return password_verify($password . self::getSalt(), $hash);
    }
}