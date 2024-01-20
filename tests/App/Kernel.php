<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Tests\App;

use Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle;
use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\ChangelogBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

final class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new SecurityBundle();
        yield new DoctrineMongoDBBundle();
        yield new ChangelogBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', ['test' => true]);

        $container->extension('doctrine_mongodb', [
            'connections' => [
                'default' => [
                    'server' => '%env(resolve:MONGODB_URL)%',
                ],
            ],
            'default_database' => 'test_database',
            'document_managers' => [
                'default' => [
                    'auto_mapping' => true,
                    'mappings' => [
                        'Document' => [
                            'type' => 'attribute',
                            'dir' => __DIR__ . '/Document',
                            'prefix' => __NAMESPACE__ . '\Document',
                        ],
                    ],
                ],
            ],
        ]);

        $container->extension('security', [
            'firewalls' => [
                'main' => [],
            ],
        ]);

        $container
            ->services()
            ->load(__NAMESPACE__ . '\MessageHandler\\', __DIR__ . '/MessageHandler')
            ->autoconfigure();
    }
}
