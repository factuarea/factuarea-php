<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

final class Validation
{
    public static function date(string $date): void
    {
        $value = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        if ($value === false || $value->format('Y-m-d') !== $date) {
            throw new \InvalidArgumentException('Opportunity dates must be exact valid YYYY-MM-DD strings.');
        }
    }

    public static function instant(string $instant): void
    {
        try {
            new \DateTimeImmutable($instant);
            $errors = \DateTimeImmutable::getLastErrors();
            if ($errors !== false && ($errors['warning_count'] !== 0 || $errors['error_count'] !== 0)) {
                throw new \InvalidArgumentException('Opportunity timestamp is invalid.');
            }
        } catch (\Exception $error) {
            throw new \InvalidArgumentException('Opportunity timestamp is invalid.', previous: $error);
        }
    }

    /** @param array<string,true> $seen */
    public static function unique(array &$seen, string $key): void
    {
        if (isset($seen[$key])) {
            throw new \InvalidArgumentException('Duplicate native Opportunity identity.');
        }
        $seen[$key] = true;
    }

    public static function cursor(string $cursor): void
    {
        if (trim($cursor) === '' || strlen($cursor) > 8192 || ! mb_check_encoding($cursor, 'UTF-8')) {
            throw new \InvalidArgumentException('The native opaque cursor must contain 1 to 8192 bytes.');
        }
    }

    public static function header(string $value): void
    {
        if ($value === '' || preg_match('/[\x00-\x1f\x7f]/', $value) === 1) {
            throw new \InvalidArgumentException('Invalid native header override.');
        }
    }
}
