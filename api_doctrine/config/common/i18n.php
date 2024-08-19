<?php
declare(strict_types=1);

use PhpLab\Application\Interface\Settings\SettingsInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;

return [
    Translator::class => function (ContainerInterface $c) {
        /** @var SettingsInterface $settings */
        $settings = $c->get(SettingsInterface::class);
        $i18nSettings = $settings->get('i18n');

        $translator = new Translator($i18nSettings['lang']);
        $translator->addLoader('php', new PhpFileLoader());
        $translator->addLoader('xlf', new XliffFileLoader());

        foreach ($i18nSettings['resources'] as $resource) {
            $translator->addResource(...$resource);
        }

        return $translator;
    },

    'config' => [
        'i18n' => [
            'lang' =>  'ru',
            'resources' => [
                [
                    'xlf',
                    ROOT_DIR . '/vendor/symfony/validator/Resources/translations/validators.ru.xlf',
                    'ru',
                    'validators',
                ],
                [
                    'php',
                    ROOT_DIR . '/i18n/ru/user.php',
                    'ru',
                ],
                [
                    'php',
                    ROOT_DIR . '/i18n/ru/exception.php',
                    'ru',
                ],

                /*
                [
                    'xlf',
                    ROOT_DIR . '/vendor/symfony/validator/Resources/translations/validators.en.xlf',
                    'en',
                    'validators',
                ],
                [
                    'php',
                    ROOT_DIR . '/i18n/en/user.php',
                    'en',
                ],
                [
                    'php',
                    ROOT_DIR . '/i18n/en/exception.php',
                    'en',
                ],
                */
            ],
        ],
    ],
];