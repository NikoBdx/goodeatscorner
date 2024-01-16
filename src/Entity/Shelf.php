<?php

namespace App\Entity;

use App\Repository\ShelfRepository;
use App\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;

#[HasLifecycleCallbacks()]
#[ORM\Entity(repositoryClass: ShelfRepository::class)]
class Shelf
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'shelf', targetEntity: Family::class)]
    private Collection $families;

    public function __construct()
    {
        $this->families = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Family>
     */
    public function getFamilies(): Collection
    {
        return $this->families;
    }

    public function addFamily(Family $family): static
    {
        if (!$this->families->contains($family)) {
            $this->families->add($family);
            $family->setShelfId($this);
        }

        return $this;
    }

    public function removeFamily(Family $family): static
    {
        if ($this->families->removeElement($family)) {
            // set the owning side to null (unless already changed)
            if ($family->getShelfId() === $this) {
                $family->setShelfId(null);
            }
        }

        return $this;
    }
}
