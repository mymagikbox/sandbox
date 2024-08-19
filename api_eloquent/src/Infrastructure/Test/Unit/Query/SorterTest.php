<?php
declare(strict_types=1);

namespace PhpLab\Infrastructure\Test\Unit\Query;

use PhpLab\Infrastructure\Query\Sorter;
use Tests\TestCase;

class SorterTest extends TestCase
{
    public function testDefaultData(): void
    {
        $pagDefault = Sorter::create([]);

        self::assertNotEmpty($pagDefault->fieldName);
        self::assertNotEmpty($pagDefault->sortDirection);

        self::assertEquals('id', $pagDefault->fieldName);
        self::assertEquals(Sorter::SORT_DESC, $pagDefault->sortDirection);
        self::assertEquals([
            'sort_by'       => 'id',
            'sort_order'    => Sorter::SORT_DESC,
        ], $pagDefault->jsonSerialize());
    }

    public function testSetupData(): void
    {
        $pagDefault = Sorter::create([
            "sortBy" => "username",
            "sortOrder" => "asc",
        ]);

        self::assertNotEmpty($pagDefault->fieldName);
        self::assertNotEmpty($pagDefault->sortDirection);

        self::assertEquals('username', $pagDefault->fieldName);
        self::assertEquals(Sorter::SORT_ASC, $pagDefault->sortDirection);
        self::assertEquals([
            'sort_by'       => 'username',
            'sort_order'    => Sorter::SORT_ASC,
        ], $pagDefault->jsonSerialize());
    }

    public function testSetupErrorData(): void
    {
        $pagDefault = Sorter::create([
            "sort-by" => "",
            "sort-order" => "1asc",
        ]);

        self::assertNotEmpty($pagDefault->fieldName);
        self::assertNotEmpty($pagDefault->sortDirection);

        self::assertEquals('id', $pagDefault->fieldName);
        self::assertEquals(Sorter::SORT_DESC, $pagDefault->sortDirection);
        self::assertEquals([
            'sort_by'       => 'id',
            'sort_order'    => Sorter::SORT_DESC,
        ], $pagDefault->jsonSerialize());
    }

    public function testSetupErrorData2(): void
    {
        $pagDefault = Sorter::create([
            "sort-by" => "",
            "sort-order" => "",
        ]);

        self::assertNotEmpty($pagDefault->fieldName);
        self::assertNotEmpty($pagDefault->sortDirection);

        self::assertEquals('id', $pagDefault->fieldName);
        self::assertEquals(Sorter::SORT_DESC, $pagDefault->sortDirection);
        self::assertEquals([
            'sort_by'       => 'id',
            'sort_order'    => Sorter::SORT_DESC,
        ], $pagDefault->jsonSerialize());
    }
}