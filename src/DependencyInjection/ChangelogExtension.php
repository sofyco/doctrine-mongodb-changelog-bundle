<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\DependencyInjection;

use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\EventListener\DocumentChangelogListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class ChangelogExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $listener = new Definition(DocumentChangelogListener::class);
        $listener->setAutowired(true);
        $listener->setAutoconfigured(true);

        $container->setDefinition(DocumentChangelogListener::class, $listener);
    }
}
