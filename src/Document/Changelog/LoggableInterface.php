<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\ChangelogBundle\Document\Changelog;

interface LoggableInterface
{
    public function getId(): string;

    public function getLoggableFields(): array;
}
