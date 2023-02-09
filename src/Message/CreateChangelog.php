<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message;

final readonly class CreateChangelog
{
    public function __construct(public ?string $userIdentifier, public string $documentClassName, public string $documentId, public string $fieldName, public mixed $oldValue, public mixed $newValue)
    {
    }
}
