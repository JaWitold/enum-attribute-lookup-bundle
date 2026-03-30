<?php

declare(strict_types=1);

namespace JaWitold\EnumAttributeLookupBundle\DependencyInjection\EnumAttributes;

use JaWitold\EnumAttributeLookupBundle\AttributeLookupTrait;
use JaWitold\EnumAttributeLookupBundle\Contract\EnumCaseAttributeInterface;
use JaWitold\EnumAttributeLookupBundle\DependencyInjection\EnumRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
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
            ->in($projectDir.'/src')
            ->name('*.php')
        ;

        $enumMap = [];

        foreach ($finder as $file) {
            if (!enum_exists($enumFqcn = $extractor->extract($file->getPathname()))) {
                continue;
            }

            try {
                $reflection = new \ReflectionEnum($enumFqcn);
            } catch (\ReflectionException) {
                continue;
            }

            if (!in_array(AttributeLookupTrait::class, $reflection->getTraitNames(), true)) {
                continue;
            }

            $enumMap[$enumFqcn] = [];

            foreach ($reflection->getCases() as $case) {
                $attributes = [];
                foreach ($case->getAttributes() as $key => $attribute) {
                    if (!in_array(EnumCaseAttributeInterface::class, new \ReflectionClass($attribute)->getInterfaceNames(), true)) {
                        continue;
                    }

                    $container->setDefinition(
                        $id = sprintf('%s.%s.%s.%d', $enumFqcn, $case->getName(), $attributeFqcn = $attribute->getName(), $key),
                        new Definition($attributeFqcn, $attribute->getArguments()),
                    );

                    $attributes[] = new Reference($id);
                }

                if ([] === $attributes) {
                    continue;
                }

                $enumMap[$enumFqcn][$case->getName()] = $attributes;
            }
        }

        $container->setDefinition(
            EnumRegistry::class,
            new Definition(EnumRegistry::class, [$enumMap])
                ->setPublic(true),
        );
    }
}
