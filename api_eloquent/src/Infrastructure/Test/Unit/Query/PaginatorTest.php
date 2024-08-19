<?php
declare(strict_types=1);

namespace PhpLab\Infrastructure\Test\Unit\Query;

use PhpLab\Infrastructure\Query\Paginator;
use Tests\TestCase;

class PaginatorTest extends TestCase
{
    public function testDefaultData(): void
    {
        $pagDefault = Paginator::create([]);

        $pagDefault->setTotal(40);

        self::assertNotEmpty($pagDefault->page);
        self::assertNotEmpty($pagDefault->limit);
        self::assertNotEmpty($pagDefault->getTotal());

        self::assertEquals(1, $pagDefault->page);
        self::assertEquals(Paginator::DEFAULT_LIMIT, $pagDefault->limit);
        self::assertEquals(40, $pagDefault->getTotal());
        self::assertEquals([
            'total'     => 40,
            'page'      => 1,
            'limit'     => Paginator::DEFAULT_LIMIT
        ], $pagDefault->jsonSerialize());
    }

    public function testSetupData(): void
    {
        $pagDefault = Paginator::create([
            "page" => "2",
            "limit" => "20",
        ]);

        $pagDefault->setTotal(40);
        self::assertNotEmpty($pagDefault->page);
        self::assertNotEmpty($pagDefault->limit);

        self::assertEquals(2, $pagDefault->page);
        self::assertEquals(20, $pagDefault->limit);
        self::assertEquals(40, $pagDefault->getTotal());
        self::assertEquals([
            'total'     => 40,
            'page'      => 2,
            'limit'     => 20
        ], $pagDefault->jsonSerialize());
    }

    public function testErrorData(): void
    {
        $pagDefault = Paginator::create([
            "page" => "",
            "limit" => "",
        ]);

        $pagDefault->setTotal(50);
        self::assertNotEmpty($pagDefault->page);
        self::assertNotEmpty($pagDefault->limit);
        self::assertNotEmpty($pagDefault->getTotal());

        self::assertEquals(1, $pagDefault->page);
        self::assertEquals(Paginator::DEFAULT_LIMIT, $pagDefault->limit);
        self::assertEquals(50, $pagDefault->getTotal());
        self::assertEquals([
            'total'     => 50,
            'page'      => 1,
            'limit'     => Paginator::DEFAULT_LIMIT
        ], $pagDefault->jsonSerialize());
    }
}