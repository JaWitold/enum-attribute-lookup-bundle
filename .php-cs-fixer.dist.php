<?php

declare(strict_types=1);

return new PhpCsFixer\Config()
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        'declare_strict_types' => ['preserve_existing_declaration' => true],
        'modifier_keywords' => [
            'elements' => ['property', 'const'],
        ],
    ])
    ->setRiskyAllowed(true)
    ->setFinder(new PhpCsFixer\Finder()->in(__DIR__));
