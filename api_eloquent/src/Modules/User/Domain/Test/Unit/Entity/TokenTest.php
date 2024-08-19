<?php
declare(strict_types=1);

namespace PhpLab\Domain\Modules\User\Test\Unit\Entity;

use PhpLab\Domain\Modules\User\Entity\Token;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Webmozart\Assert\InvalidArgumentException;

/***
 * @covers Id
*/
class TokenTest extends TestCase
{
    public function testSuccess(): void
    {
        $token = new Token(
            $value = Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable(),
        );

        self::assertEquals($value, $token->getValue());
        self::assertEquals($expires, $token->getExpires());
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();

        $id = new Token(
            mb_strtoupper($value),
            new DateTimeImmutable()
        );

        self::assertEquals($value, $id->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Token('12345', new DateTimeImmutable());
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Token('',new DateTimeImmutable() );
    }
}