<?php

namespace App\Enums;

enum PostVisibilityEnum: string
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';

    /**
     * Get all types values as an array.
     *
     * @return array
     */
    public static function all(): array
    {
        return array_map(fn(self $type) => $type->value, self::cases());
    }

    public static function getDefaultItem(): self
    {
        return self::PUBLIC;
    }

    public static function random(): self
    {
        $cases = self::cases();
        return $cases[array_rand($cases)];
    }

}
