<?php
declare(strict_types=1);

namespace PhpLab\Modules\Catalog\Domain\Category\Model;

use DateTimeImmutable;
use PhpLab\Domain\Seo;
use PhpLab\Domain\Interface\Model\DomainModelInterface;

final class Category implements DomainModelInterface
{
    const TABLE = 'catalog_category';
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
        Seo $seo,
        ?self $parent = null
    )
    {

    }


}