<?php
declare(strict_types=1);

use PhpLab\Admin\User\Domain\Entity\Password;
use PhpLab\Infrastructure\Db\Migrations\BaseMigration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

final class InitMigration extends BaseMigration
{
    protected string $table = 'users';
    protected string $tableAccessToken = 'access_tokens';

    public function up(): void
    {
        $this->getSchema()->create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('role', ['admin', 'manager'])->nullable(false)->default('manager');
            $table->string('username', 255)->unique()->nullable(false);
            $table->string('password_hash', 255)->nullable(false);
            $table->enum('status', ['disabled', 'active', 'deleted'])->nullable(false)->default('disabled');
            $table->dateTime('created_at')->useCurrent(); // Set TIMESTAMP columns to use CURRENT_TIMESTAMP as default value.
            $table->dateTime('updated_at')->useCurrentOnUpdate(); // Set TIMESTAMP columns to use CURRENT_TIMESTAMP when a record is updated (MySQL).
            $table->softDeletes();
        });

        DB::transaction(function () {
            $password = Password::create('Password01!');

            DB::table($this->table)->insert(
                array(
                    'role' => 'admin',
                    'status' => 'active',
                    'username' => 'admin',
                    'password_hash' => $password->getHash(),
                )
            );
        });

        $this->getSchema()->create($this->tableAccessToken, function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('refresh_token', 255)->unique()->nullable(false);
            $table->dateTime('expired_at')->nullable(false);
            $table->unsignedBigInteger('refresh_count')->default(0)->nullable(false);
            $table->string('device_info', 255)->nullable();
            $table->dateTime('created_at')->useCurrent(); // Set TIMESTAMP columns to use CURRENT_TIMESTAMP as default value.
            $table->dateTime('updated_at')->useCurrentOnUpdate(); // Set TIMESTAMP columns to use CURRENT_TIMESTAMP when a record is updated (MySQL).
        });
    }

    public function down(): void
    {
        $this->getSchema()->drop($this->table);
        $this->getSchema()->drop($this->tableAccessToken);
    }
}
