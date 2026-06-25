<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return new Config()
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        '@PHP8x2Migration' => true,
        '@PHP8x2Migration:risky' => true,
        'yoda_style' => true,
        'declare_strict_types' => ['strategy' => 'enforce'],
        'blank_line_after_opening_tag' => true,
        'concat_space' => ['spacing' => 'one'],
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'nullable_type_declaration_for_default_null_value' => true,
        'nullable_type_declaration' => ['syntax' => 'union'],
        'phpdoc_align' => ['align' => 'left'],
        'comment_to_phpdoc' => false,
        'no_trailing_whitespace_in_comment' => true,
        'strict_param' => true,
        'strict_comparison' => true,
        'void_return' => true,
        'is_null' => true,
        'native_function_invocation' => false,
        'native_constant_invocation' => [
            'fix_built_in' => false,
            'include' => ['@compiler_optimized'],
            'scope' => 'namespaced',
        ],
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters', 'match'],
        ],
        'global_namespace_import' => [
            'import_classes' => false,
            'import_constants' => false,
            'import_functions' => false,
        ],
        'php_unit_strict' => true,
        'php_unit_internal_class' => false,
        'attribute_empty_parentheses' => true,
        'method_argument_space' => ['attribute_placement' => 'same_line'],
        'ordered_attributes' => [
            'sort_algorithm' => 'alpha',
        ],
        'use_arrow_functions' => false,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'static',
        ],
        'phpdoc_to_comment' => false,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none',
        ],
        'ordered_types' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none',
        ],
        'mb_str_functions' => false,
        'no_superfluous_phpdoc_tags' => ['allow_mixed' => true,
            'remove_inheritdoc' => true,
        ],
        'date_time_immutable' => true,
        'return_assignment' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder(new Finder()->in(__DIR__));
