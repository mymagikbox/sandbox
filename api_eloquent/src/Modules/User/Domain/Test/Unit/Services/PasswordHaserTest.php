<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Test\Unit\Services;

use PhpLab\Modules\User\Domain\Services\PasswordHasher;
use Tests\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class PasswordHaserTest extends TestCase
{
    public function testSuccess(): void
    {
        $haser = new PasswordHasher();

        $passwordHash = $haser->generateHash($password = 'Qwerty');

        self::assertNotEmpty($passwordHash);
        self::assertNotEquals($password, $passwordHash);
    }

    public function testEmpty(): void
    {
        $haser = new PasswordHasher();
        $this->expectException(InvalidArgumentException::class);

        $passwordHash = $haser->generateHash('');
    }

    public function testValidate(): void
    {
        $haser = new PasswordHasher();
        $passwordHash = $haser->generateHash($password = 'Qwerty');

        self::assertTrue($haser->validate($password, $passwordHash));
        self::assertFalse($haser->validate('wrong-pass', $passwordHash));
    }
}