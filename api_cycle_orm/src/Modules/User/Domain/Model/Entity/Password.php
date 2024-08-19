<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Model\Entity;

use PhpLab\Modules\User\Domain\Util\PasswordHasher;

class Password
{
    private string $hash;

    public static function create(string $value): self
    {
        $field = new self();
        $field->hash = PasswordHasher::generateHash($value);
        return $field;
    }

    public function isValidPassword(string $password): bool
    {
        return PasswordHasher::validate($password, $this->hash);
    }
}