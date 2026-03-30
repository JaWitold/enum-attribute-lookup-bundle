<?php

declare(strict_types=1);

namespace JaWitold\EnumAttributeLookupBundle;

use JaWitold\EnumAttributeLookupBundle\Contract\EnumCaseAttributeInterface;
use JaWitold\EnumAttributeLookupBundle\DependencyInjection\EnumRegistry;

/**
 * Adds attribute-lookup methods to an enum.
 *
 * Use this trait on any enum whose cases carry PHP attributes implementing
 * {@see EnumCaseAttributeInterface}.
 * The bundle automatically discovers and registers those attributes on kernel boot.
 *
 * Example:
 * <pre>
 *     enum Status
 *     {
 *         use AttributeLookupTrait;
 *
 *         #[Label('Active')]
 *         case ACTIVE;
 *
 *         #[Label('Inactive')]
 *         case INACTIVE;
 *     }
 *
 *     // All cases that have a Label attribute:
 *     $cases = Status::getCasesByAttribute(Label::class);
 *
 *     // All Label attributes on a specific case:
 *     $labels = Status::getAttributes(Status::ACTIVE, fn($a) => $a instanceof Label);
 * </pre>
 */
trait AttributeLookupTrait
{
    public static function getCasesByAttribute(string $fqcn): array
    {
        return self::getCases(static fn (object $attribute) => $attribute instanceof $fqcn);
    }

    /**
     * @param callable(object $a): bool $filter
     *
     * @return list<\UnitEnum>
     */
    public static function getCases(callable $filter
 = static function (): bool {
     return true;
 }): array {
        $result = [];
        foreach (EnumRegistry::getMap(self::class) as $name => $attributes) {
            if (!array_any($attributes, $filter)) {
                continue;
            }

            $result[] = constant(self::class.'::'.$name);
        }

        return $result;
    }

    /**
     * @return list<object>
     */
    public static function getAttributes(self $case, callable $filter
 = static function (): bool {
     return true;
 }): array {
        return array_values(array_filter(
            EnumRegistry::getMap(self::class)[$case->name] ?? [],
            $filter
        ));
    }
}
