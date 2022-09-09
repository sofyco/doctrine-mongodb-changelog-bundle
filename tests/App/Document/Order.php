<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Tests\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'orders')]
final class Order
{
    #[MongoDB\Id]
    public string $id;

    #[MongoDB\Field]
    public string $sku;

    public function __construct(string $sku)
    {
        $this->sku = $sku;
    }
}
