<?php

namespace App\Enums;

enum MediaTypeEnum: string
{
    case PICTURE = 'picture';
    case VIDEO = 'video';

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
        return self::PICTURE;
    }

    public static function random(): self
    {
        $cases = self::cases();
        return $cases[array_rand($cases)];
    }

}
