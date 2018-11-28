<?php

namespace App\Entity;

use App\Entity\Traits\PublishedTrait;
use App\Entity\Traits\SoftDeletedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProposalRepository")
 */
class Proposal
{
    use PublishedTrait;
    use SoftDeletedTrait;
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentary", mappedBy="proposal", orphanRemoval=true)
     */
    private $commentaries;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Notation", mappedBy="proposal", cascade={"persist", "remove"})
     */
    private $notation;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $slug = null;

    public function __construct()
    {
        $this->commentaries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Commentary[]
     */
    public function getCommentaries(): Collection
    {
        return $this->commentaries;
    }

    public function addCommentary(Commentary $commentary): self
    {
        if (!$this->commentaries->contains($commentary)) {
            $this->commentaries[] = $commentary;
            $commentary->setProposal($this);
        }

        return $this;
    }

    public function removeCommentary(Commentary $commentary): self
    {
        if ($this->commentaries->contains($commentary)) {
            $this->commentaries->removeElement($commentary);
            // set the owning side to null (unless already changed)
            if ($commentary->getProposal() === $this) {
                $commentary->setProposal(null);
            }
        }

        return $this;
    }

    public function getNotation(): ?Notation
    {
        return $this->notation;
    }

    public function setNotation(Notation $notation): self
    {
        $this->notation = $notation;

        // set the owning side of the relation if necessary
        if ($this !== $notation->getProposal()) {
            $notation->setProposal($this);
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
    /**
     * @param string $slug
     *
     * @return Proposal
     */
    public function setSlug(string $slug):? self
    {
        $this->slug = $slug;
        return $this;
    }
}
