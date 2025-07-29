<?php

namespace App\Entity;

use App\Repository\CepagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CepagesRepository::class)]
class Cepages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cepage = null;

    /**
     * @var Collection<int, Bouteille>
     */
    #[ORM\OneToMany(targetEntity: Bouteille::class, mappedBy: 'cepage')]
    private Collection $bouteilles;

    public function __construct()
    {
        $this->bouteilles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCepage(): ?string
    {
        return $this->cepage;
    }

    public function setCepage(string $cepage): static
    {
        $this->cepage = $cepage;

        return $this;
    }

    /**
     * @return Collection<int, Bouteille>
     */
    public function getBouteilles(): Collection
    {
        return $this->bouteilles;
    }

    public function addBouteille(Bouteille $bouteille): static
    {
        if (!$this->bouteilles->contains($bouteille)) {
            $this->bouteilles->add($bouteille);
            $bouteille->setCepage($this);
        }

        return $this;
    }

    public function removeBouteille(Bouteille $bouteille): static
    {
        if ($this->bouteilles->removeElement($bouteille)) {
            // set the owning side to null (unless already changed)
            if ($bouteille->getCepage() === $this) {
                $bouteille->setCepage(null);
            }
        }

        return $this;
    }
}
