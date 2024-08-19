<?php
declare(strict_types=1);

namespace PhpLab\Domain\Helper;

use DateTimeImmutable;
use DateTimeZone;
use PhpLab\Application\Factory\App\HttpAppFactory;
use PhpLab\Application\Interface\Settings\SettingsInterface;

class DateTimeHelper
{
    public const DEFAULT_FORMAT = 'Y-m-d H:i:s';
    public const DATE_FORMAT = 'Y-m-d';

    public static function getTZ()
    {
        $container = HttpAppFactory::getContainer();
        /** @var SettingsInterface $settings */
        $settings = $container->get(SettingsInterface::class);

        return $settings->get('timezone') ?? 'UTC';
    }

    public static function applyTZ(DateTimeImmutable $dateTime, ?string $tz = null): DateTimeImmutable
    {
        $tz = $tz ?? static::getTZ();

        $dateTime->setTimezone(new DateTimeZone($tz));

        return $dateTime;
    }

    public static function createDateTime(string $dateString = 'now', ?string $tz = null): DateTimeImmutable
    {
        $dateTime = new DateTimeImmutable($dateString);
        $tz = $tz ?? static::getTZ();

        return self::applyTZ($dateTime, $tz);
    }

    public static function createFromTimeStamp(int $utc): DateTimeImmutable
    {
        $dateTime = new DateTimeImmutable();
        $dateTime->setTimestamp($utc);

        return $dateTime;
    }

    public static function format(DateTimeImmutable $dateTime = null, $format = self::DEFAULT_FORMAT): ?string
    {
        if (!$dateTime) {
            return null;
        }
        return $dateTime->format($format);
    }
}