<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Message;

final readonly class DeleteChangelog
{
    /**
     * @var class-string
     */
    public string $documentClassName;

    public string $documentId;

    public function __construct(string $documentClassName, string $documentId)
    {
        $this->documentClassName = $documentClassName;
        $this->documentId = $documentId;
    }
}
