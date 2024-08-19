<?php
declare(strict_types=1);

namespace Unit\Modules\User\Entity;

use PhpLab\Domain\Modules\User\Entity\Username;
use Tests\TestCase;
use Webmozart\Assert\InvalidArgumentException;

/***
 * @covers Id
*/
class UsernameTest extends TestCase
{
    public function testSuccess(): void
    {
        $username = new Username($value = 'admin');

        self::assertEquals($value, $username->getValue());
    }

    public function testCase(): void
    {
        $username = new Username($value = 'Admin');

        self::assertEquals('admin', $username->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Username('Test$');
    }

    public function testIncorrect_2(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Username('Test$asd');
    }

    public function testIncorrect_3(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Username('/asd');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Username('');
    }
}