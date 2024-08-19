<?php
declare(strict_types=1);

use Cycle\ORM\Schema;
use Cycle\ORM\Relation;
use Cycle\ORM\Mapper\Mapper;

use PhpLab\Modules\User\Domain\Model\RefreshToken;
use PhpLab\Modules\User\Domain\Model\User;

return [
    RefreshToken::TABLE => [
        Schema::ROLE => 'r',
        Schema::ENTITY => RefreshToken::class,
        Schema::MAPPER => Mapper::class,
        Schema::DATABASE => 'default',
        Schema::TABLE => RefreshToken::TABLE,
        Schema::PRIMARY_KEY => ['user_id', 'refresh_token'],
        Schema::COLUMNS => [
            // property => column
            'userId'        => 'user_id',
            'refreshToken'  => 'refresh_token',
            'refreshCount'  => 'refresh_count',
            'expiredAt'     => 'expired_at',
            'deviceInfo'    => 'device_info',
        ],
        Schema::TYPECAST => [
            // object property => type
            'userId'        => 'int',
            'refreshToken'  => 'string',
            'refreshCount'  => 'int',
            'expiredAt'     => 'datetime',
            'deviceInfo'    => 'string',
        ],
        Schema::RELATIONS => [
            // relation Name
            User::TABLE => [
                Relation::TYPE => Relation::BELONGS_TO,
                Relation::TARGET => User::TABLE,
                Relation::SCHEMA => [
                    Relation::CASCADE  => true,
                    Relation::NULLABLE => false,
                    Relation::INNER_KEY => 'user_id',
                    Relation::OUTER_KEY => 'id',
                ],
            ]
        ]
    ],
];