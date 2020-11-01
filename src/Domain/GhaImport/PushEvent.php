<?php

declare(strict_types = 1);

namespace App\Domain\GhaImport;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class PushEvent
{
    /** @var int */
    private $id;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var string */
    private $repoName;

    /** @var Collection|Commit[] */
    private $commits;

    final public function __construct(
        \DateTimeInterface $createdAt,
        string $repoName
    ) {
        $this->createdAt = $createdAt;
        $this->repoName = $repoName;
        $this->commits = new ArrayCollection();
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getRepoName(): string
    {
        return $this->repoName;
    }

    public function addCommit(Commit $commit): self
    {
        if (!$this->commits->contains($commit)) {
            $this->commits->add($commit);
        }

        return $this;
    }

    public function getCommits(): Collection
    {
        return $this->commits;
    }
}
