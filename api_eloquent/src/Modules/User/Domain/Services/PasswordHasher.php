<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Services;

use RuntimeException;
use Webmozart\Assert\Assert;

class PasswordHasher
{
    public function generateHash(string $password, array $options = ['cost' => 12]): string
    {
        Assert::notEmpty($password, 'auth.password.not_blank');

        /** @var false|null|string $hash */
        $hash = password_hash($password, PASSWORD_BCRYPT, $options);

        if ($hash === null) {
            throw new RuntimeException("Invalid hash algorithm");
        }

        if ($hash === false) {
            throw new RuntimeException("Unable to generate hash");
        }

        return $hash;
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}