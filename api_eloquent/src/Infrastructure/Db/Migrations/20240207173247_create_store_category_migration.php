<?php
declare(strict_types=1);


use PhpLab\Infrastructure\Db\Migrations\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;

final class CreateStoreCategoryMigration extends BaseMigration
{
	protected string $table = 'catalog_category';

        public function up(): void
        {
            $this->getSchema()->create($this->table, function(Blueprint $table) {
				$table->bigIncrements('id');
                $table->bigInteger('parent_id')->nullable();
                $table->string('slug', 255)->unique()->nullable(false);
                $table->string('title', 255)->unique()->nullable(false);
                $table->smallInteger('status')->nullable(false)->default(1);
                $table->string('seo_title', 255)->unique()->nullable(false);
                $table->string('seo_keywords', 255)->unique()->nullable();
                $table->string('seo_description', 255)->unique()->nullable();
                $table->dateTime('created_at')->useCurrent(); // Set TIMESTAMP columns to use CURRENT_TIMESTAMP as default value.
                $table->dateTime('updated_at')->useCurrentOnUpdate(); // Set TIMESTAMP columns to use CURRENT_TIMESTAMP when a record is updated (MySQL).
			});
        }

        public function down(): void
        {
            $this->getSchema()->drop($this->table);
        }
}
