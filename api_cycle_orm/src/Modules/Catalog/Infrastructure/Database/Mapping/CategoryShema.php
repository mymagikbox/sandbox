<?php
declare(strict_types=1);

use Cycle\ORM\Schema;
use Cycle\ORM\Relation;
use Cycle\ORM\Mapper\Mapper;
use PhpLab\Modules\Catalog\Domain\Category\Model\Category;

return [
    Category::TABLE => [
        Schema::ENTITY => Category::class,
        Schema::MAPPER => Mapper::class,
        Schema::DATABASE => 'default',
        Schema::TABLE => Category::TABLE,
        Schema::PRIMARY_KEY => 'id',
        Schema::COLUMNS => [
            // property => column
            'id',
            'parent_id',
            'title',
            'slug',
            'seo',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
            'deletedAt' => 'deleted_at',
        ],
        Schema::TYPECAST => [
            'id' => 'int',
        ],
        Schema::RELATIONS => [

        ]
    ],
];