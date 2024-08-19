<?php
declare(strict_types=1);

use Slim\App;
use PhpLab\Application\Interface\Validator\ValidatorInterface;
use PhpLab\Application\Middleware\ValidationMiddleware;
use Psr\Container\ContainerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;
use PhpLab\Application\Validator\Validator;

return [
    SymfonyValidatorInterface::class => function (ContainerInterface $container) {
        $translator = $container->get(Translator::class);

        return Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setTranslator($translator)
            ->setTranslationDomain('validators')
            ->getValidator();
    },

    ValidationMiddleware::class => function (ContainerInterface $container) {
        /** @var App $app */
        $app = $container->get(App::class);

        return new ValidationMiddleware(
            $container->get(Translator::class),
            $app->getResponseFactory()
        );
    },

    ValidatorInterface::class => DI\get(Validator::class),
];