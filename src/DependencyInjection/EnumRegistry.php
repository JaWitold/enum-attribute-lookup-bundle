<?php

declare(strict_types=1);

namespace JaWitold\EnumAttributeLookupBundle\DependencyInjection;

/**
 * @internal
 */
class EnumRegistry
{
    /**
     * @var array<class-string<\UnitEnum>, array<string, list<object>>>
     */
    private static array $map = [];

    /**
     * @param array<class-string<\UnitEnum>, array<string, list<object>>> $map
     */
    public function __construct(array $map)
    {
        self::$map = $map;
    }

    /**
     * @param class-string<\UnitEnum> $fqcn
     *
     * @return array<string, list<object>>
     */
    public static function getMap(string $fqcn): array
    {
        return self::$map[$fqcn] ?? [];
    }
}
