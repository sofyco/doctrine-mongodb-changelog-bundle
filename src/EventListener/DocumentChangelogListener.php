<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\EventListener;

use Doctrine\Bundle\MongoDBBundle\Attribute\AsDocumentListener;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Document\Changelog\LoggableInterface;
use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsDocumentListener(event: Events::preUpdate, method: Events::preUpdate)]
#[AsDocumentListener(event: Events::preRemove, method: Events::preRemove)]
final readonly class DocumentChangelogListener
{
    public function __construct(private DocumentManager $dm, private MessageBusInterface $messageBus, private Security $security)
    {
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $document = $args->getObject();

        if (!$document instanceof LoggableInterface) {
            return;
        }

        foreach ($this->dm->getUnitOfWork()->getDocumentChangeSet($args->getObject()) as $fieldName => $set) {
            if (\in_array($fieldName, $document->getLoggableFields(), true)) {
                [$oldValue, $newValue] = $set;

                $message = new Message\CreateChangelog(
                    userIdentifier: $this->security->getUser()?->getUserIdentifier(),
                    documentClassName: $document::class,
                    documentId: $document->getId(),
                    fieldName: $fieldName,
                    oldValue: $oldValue,
                    newValue: $newValue,
                );

                $this->messageBus->dispatch($message);
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $document = $args->getObject();

        if (!$document instanceof LoggableInterface) {
            return;
        }

        $message = new Message\DeleteChangelog(documentClassName: $document::class, documentId: $document->getId());

        $this->messageBus->dispatch($message);
    }
}
