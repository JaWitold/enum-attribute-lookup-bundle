<?php

declare(strict_types=1);

namespace JaWitold\EnumAttributeLookupBundle\DependencyInjection;

use JaWitold\EnumAttributeLookupBundle\AttributeLookupTrait;
use JaWitold\EnumAttributeLookupBundle\EnumRegistry;
use JaWitold\EnumAttributeLookupBundle\Interface\EnumCaseAttributeInterface;
use JaWitold\FqcnExtractor\FqcnExtractor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Finder\Finder;

class EnumAttributesCompilerPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        $extractor = new FqcnExtractor();
        $projectDir = $container->getParameter('kernel.project_dir');

        assert(is_string($projectDir));

        $finder = new Finder()
            ->files()
            ->in($projectDir . '/src')
            ->name('*.php');

        $enumMap = [];

        foreach ($finder as $file) {
            if (!enum_exists($enumFqcn = $extractor->extract($file->getPathname()) ?? '')) {
                continue;
            }

            try {
                $reflectionEnum = new \ReflectionEnum($enumFqcn);
            } catch (\ReflectionException) {
                continue;
            }

            $traits = class_uses($enumFqcn);
            if (!is_array($traits) || !isset($traits[AttributeLookupTrait::class])) {
                continue;
            }

            foreach ($reflectionEnum->getCases() as $case) {
                foreach ($case->getAttributes() as $attribute) {
                    $attrName = $attribute->getName();
                    if (!class_exists($attrName)) {
                        continue;
                    }

                    if (is_subclass_of($attrName, EnumCaseAttributeInterface::class)) {
                        $enumMap[$enumFqcn][$case->getName()][] = new Definition($attrName, $attribute->getArguments());
                    }
                }
            }
        }

        $container->register(EnumRegistry::class, EnumRegistry::class)
            ->setArgument('$map', $enumMap)
            ->setPublic(false);
    }
}
