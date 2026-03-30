<?php

declare(strict_types=1);

namespace JaWitold\EnumAttributeLookupBundle;

use JaWitold\EnumAttributeLookupBundle\DependencyInjection\EnumAttributes\EnumAttributesCompilerPass;
use JaWitold\EnumAttributeLookupBundle\DependencyInjection\EnumRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class JaWitoldEnumAttributeLookupBundle extends AbstractBundle
{
    #[\Override]
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new EnumAttributesCompilerPass());
    }

    #[\Override]
    public function boot(): void
    {
        parent::boot();

        $this->container?->get(EnumRegistry::class);
    }
}
