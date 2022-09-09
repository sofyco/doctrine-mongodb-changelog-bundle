<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Tests\App\MessageHandler;

use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message\DeleteChangelog;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class DeleteChangelogHandler
{
    public function __invoke(DeleteChangelog $message): void
    {
        MessageStore::$messages[] = $message;
    }
}
