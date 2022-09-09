<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Tests\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Document\Changelog\LoggableInterface;

#[MongoDB\Document(collection: 'products')]
final class Product implements LoggableInterface
{
    #[MongoDB\Id]
    public string $id;

    #[MongoDB\Field]
    public string $sku;

    #[MongoDB\Field]
    public string $name;

    public function __construct(string $sku, string $name)
    {
        $this->sku = $sku;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLoggableFields(): array
    {
        return ['sku'];
    }
}
