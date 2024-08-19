<?php
declare(strict_types=1);

namespace PhpLab\Modules\Catalog\Category\Domain\Entity;

use DateTimeImmutable;
use PhpLab\Domain\Seo;

class Category
{
    private ?int $id = null;
    private Category $parent;
    private string $title;
    private string $slug;
    private Seo $seo;
    private DateTimeImmutable $created_at;
    private DateTimeImmutable $updated_at;
    private ?DateTimeImmutable $deleted_at = null;

    public function __construct(
        string $title,
        string $slug,
        Seo $seo
    )
    {
        $this->setUsername($username);
        $this->setRole($role);
        $this->setStatus($status);
    }


}