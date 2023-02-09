<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message;

final readonly class DeleteChangelog
{
    public function __construct(public string $documentClassName, public string $documentId)
    {
    }
}
