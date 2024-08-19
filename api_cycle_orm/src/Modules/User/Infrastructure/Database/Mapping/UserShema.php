<?php
declare(strict_types=1);

use Cycle\ORM\Schema;
use Cycle\ORM\Relation;
use Cycle\ORM\Mapper\Mapper;
// use Cycle\ORM\Mapper\StdMapper;

use PhpLab\Modules\User\Domain\Enum\UserStatus;
use PhpLab\Modules\User\Domain\Enum\UserRole;
use PhpLab\Modules\User\Domain\Model\Entity\Password;
use PhpLab\Modules\User\Domain\Model\User;
use PhpLab\Modules\User\Domain\Model\RefreshToken;
use PhpLab\Modules\User\Infrastructure\Repository\Mysql\UserRepository;

return [
    User::TABLE => [
        Schema::ENTITY => User::class,
        Schema::MAPPER => Mapper::class,
        // Schema::REPOSITORY  => UserRepository::class,
        // Schema::SCOPE => UserScope::class,
        // Schema::MAPPER => StdMapper::class,
        Schema::DATABASE => 'default',
        Schema::TABLE => User::TABLE,
        Schema::PRIMARY_KEY => 'id',
        // Schema::PRIMARY_KEY => ['key1', 'key2'],
        Schema::COLUMNS => [
            // property => column
            'id',
            'username',
            'email',
            'status',
            'role',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
            'deletedAt' => 'deleted_at',
        ],
        Schema::TYPECAST => [ // я так понимаю это для воссоздания обьекта
            // object property => type
            'id'        => 'int',
            'username'  => 'string',
            'status'    =>  \Closure::fromCallable([UserStatus::class, 'make']),
            'role'      =>  \Closure::fromCallable([UserRole::class, 'make']),
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
            'deletedAt' => 'datetime',
        ],
        Schema::RELATIONS => [
            // property Name or relation name for select
            'password' => [
                Relation::TYPE => Relation::EMBEDDED,
                Relation::TARGET => Password::class,
                Relation::LOAD => Relation::LOAD_EAGER, // IMPORTANT!
                Relation::SCHEMA => [],
            ],
            'refreshToken' => [
                Relation::TYPE => Relation::HAS_MANY,
                Relation::TARGET => RefreshToken::TABLE,
                Relation::SCHEMA => [
                    Relation::CASCADE  => true,
                    Relation::INNER_KEY => 'id',
                    Relation::OUTER_KEY => 'user_id',
                ],
            ]
        ],
    ],
    Password::class => [
        Schema::MAPPER => Mapper::class,
        Schema::DATABASE => 'default',
        Schema::TABLE => User::TABLE,
        Schema::COLUMNS => [
            // property => column
            'hash' => 'password_hash',
        ],
        Schema::TYPECAST => [
            // property => type
            'hash'  => 'string',
        ],
        Schema::SCHEMA => [],
        Schema::RELATIONS => [],
    ],
];