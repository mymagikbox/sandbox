<?php
declare(strict_types=1);

namespace PhpLab\Application\Traits;

use PhpLab\Application\AppFactory;
use Symfony\Component\Translation\Translator;

trait I18nTrait
{
    public static function t(?string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        $container = AppFactory::getContainer();
        $translator = $container->get(Translator::class);

        return $translator->trans($id, $parameters, $domain, $locale);
    }
}