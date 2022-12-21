<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message;

use Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Document\Changelog\LoggableInterface;

final readonly class DeleteChangelog
{
    public function __construct(public LoggableInterface $document)
    {
    }
}
