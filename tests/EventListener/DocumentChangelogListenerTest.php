<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Tests\EventListener;

use Doctrine\ODM\MongoDB\DocumentManager;
use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message\CreateChangelog;
use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message\DeleteChangelog;
use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Tests\App\Document\Order;
use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Tests\App\Document\Product;
use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Tests\App\MessageHandler\MessageStore;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DocumentChangelogListenerTest extends KernelTestCase
{
    protected function setUp(): void
    {
        MessageStore::$messages = [];

        self::getDocumentManager()->getDocumentDatabase(Product::class)->drop();
        self::getDocumentManager()->persist(new Order(sku: '0000-0000'));
        self::getDocumentManager()->persist(new Product(sku: '0000-0000', name: 'product-1'));
        self::getDocumentManager()->flush();
    }

    public function testChangedLoggableField(): void
    {
        $sku = '0000-0000';
        /** @var Product $product */
        $product = self::getDocumentManager()->getRepository(Product::class)->findOneBy(['sku' => $sku]);
        $product->sku = '0000-0001';
        self::getDocumentManager()->flush();

        $messages = [
            new CreateChangelog(
                userIdentifier: null,
                documentClassName: Product::class,
                documentId: $product->getId(),
                fieldName: 'sku',
                oldValue: $sku,
                newValue: $product->sku,
            ),
        ];

        self::assertEquals($messages, MessageStore::$messages);
    }

    public function testChangedNonLoggableField(): void
    {
        $sku = '0000-0000';
        /** @var Product $product */
        $product = self::getDocumentManager()->getRepository(Product::class)->findOneBy(['sku' => $sku]);
        $product->name = 'product-2';
        self::getDocumentManager()->flush();

        self::assertEquals([], MessageStore::$messages);
    }

    public function testChangedNonLoggableDocument(): void
    {
        $sku = '0000-0000';
        /** @var Order $order */
        $order = self::getDocumentManager()->getRepository(Order::class)->findOneBy(['sku' => $sku]);
        $order->sku = '0000-0001';
        self::getDocumentManager()->flush();

        self::assertEquals([], MessageStore::$messages);
    }

    public function testDeleteLoggableDocument(): void
    {
        /** @var Product $product */
        $product = self::getDocumentManager()->getRepository(Product::class)->findOneBy(['sku' => '0000-0000']);
        self::getDocumentManager()->remove($product);
        self::getDocumentManager()->flush();

        $messages = [
            new DeleteChangelog(document: $product),
        ];

        self::assertEquals($messages, MessageStore::$messages);
    }

    public function testDeleteNonLoggableDocument(): void
    {
        /** @var object $order */
        $order = self::getDocumentManager()->getRepository(Order::class)->findOneBy([]);

        self::assertInstanceOf(Order::class, $order);

        self::getDocumentManager()->remove($order);
        self::getDocumentManager()->flush();

        self::assertSame([], MessageStore::$messages);
    }

    private static function getDocumentManager(): DocumentManager
    {
        return self::getContainer()->get(DocumentManager::class); // @phpstan-ignore-line
    }
}
