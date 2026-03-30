<?php

declare(strict_types=1);

namespace JaWitold\EnumAttributeLookupBundle\Contract;

use JaWitold\EnumAttributeLookupBundle\AttributeLookupTrait;

/**
 * Marker interface for PHP attributes that attach metadata to enum cases.
 *
 * Implement this interface on any #[Attribute] class whose instances should be
 * discoverable via {@see AttributeLookupTrait}. The bundle scans all enums
 * that use that trait and registers only attributes implementing this interface.
 *
 * Example:
 *
 *     #[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
 *     class Label implements EnumCaseAttributeInterface
 *     {
 *         public function __construct(public readonly string $value) {}
 *     }
 */
interface EnumCaseAttributeInterface {}
