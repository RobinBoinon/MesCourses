<?php

namespace App\Entity;

use App\Repository\ListsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListsRepository::class)
 */
class Lists
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /** @ORM\OneToMany(targetEntity="ListsProducts", mappedBy="lists") */
    protected $stockProducts;

    public function __construct()
    {
        $this->stockProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|ListsProducts[]
     */
    public function getStockProducts(): Collection
    {
        return $this->stockProducts;
    }

    public function addStockProduct(ListsProducts $stockProduct): self
    {
        if (!$this->stockProducts->contains($stockProduct)) {
            $this->stockProducts[] = $stockProduct;
            $stockProduct->setLists($this);
        }

        return $this;
    }

    public function removeStockProduct(ListsProducts $stockProduct): self
    {
        if ($this->stockProducts->removeElement($stockProduct)) {
            // set the owning side to null (unless already changed)
            if ($stockProduct->getLists() === $this) {
                $stockProduct->setLists(null);
            }
        }

        return $this;
    }
}
