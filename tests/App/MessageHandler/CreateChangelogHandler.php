<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Tests\App\MessageHandler;

use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message\CreateChangelog;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateChangelogHandler
{
    public function __invoke(CreateChangelog $message): void
    {
        MessageStore::$messages[] = $message;
    }
}
