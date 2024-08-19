<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Entity;

use PhpLab\Modules\User\Domain\Services\PasswordHasher;
use Webmozart\Assert\Assert;

class Password
{
    private string $value;

    private bool $isHash = false;

    private PasswordHasher $passwordHasher;

    public function __construct(
    )
    {
        $this->passwordHasher = new PasswordHasher();
    }

    public static function create(string $value, $isHash = false): static
    {
        $field = new static();
        $field->setValue($value, $isHash);
        return $field;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value, $isHash = false): void
    {
        $this->value = $value;
        $this->isHash = $isHash;
    }

    public function getHash(): string
    {
        return $this->isHash ?
            $this->getValue() :
            $this->passwordHasher->generateHash($this->getValue());
    }
}