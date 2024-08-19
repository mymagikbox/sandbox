<?php
declare(strict_types=1);

namespace PhpLab\Infrastructure\Db\Migrations;

use Illuminate\Support\Facades\DB;
use Phinx\Migration\AbstractMigration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Builder;

class BaseMigration extends AbstractMigration
{
    public function getSchema(): Builder
    {
        return Capsule::schema();
    }

    public function setEnumValues(
        string $table,
        string $column,
        array $values,
        bool $nullable = false,
        string $default = null
    ): void
    {
        $quotedValues = collect($values)
            ->map(function ($value) {
                return "'${value}'";
            })
            ->join(', ');

        $suffix = '';

        if (!$nullable) {
            $suffix .= ' NOT NULL';
        }

        if ($default) {
            $suffix .= " DEFAULT '${default}'";
        }

        $statement = <<<SQL
ALTER TABLE ${table} CHANGE COLUMN ${column} ${column} ENUM(${quotedValues}) ${suffix}
SQL;

        DB::statement($statement);
    }
}