<?php

declare(strict_types=1);

namespace Spatie\QueryString;

final class StringHelper
{
    public static function startsWith(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) === 0;
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, -strlen($needle)) === $needle;
    }

    public static function ensureEndsWith(string $subject, string $needle): string
    {
        if (strpos($subject, $needle) === false) {
            $subject = $subject.$needle;
        }

        return $subject;
    }

    public static function replaceLast(string $search, string $replace, string $subject): string
    {
        $position = strrpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }
}
