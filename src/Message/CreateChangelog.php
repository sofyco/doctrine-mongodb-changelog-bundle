<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message;

final class CreateChangelog
{
    public function __construct(
        public readonly ?string $userIdentifier,
        public readonly string  $documentClassName,
        public readonly string  $documentId,
        public readonly string  $fieldName,
        public readonly mixed   $oldValue,
        public readonly mixed   $newValue,
    )
    {
    }
}
