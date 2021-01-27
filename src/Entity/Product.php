<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_link;

    /** @ORM\OneToMany(targetEntity="ListsProducts", mappedBy="product") */
    protected $stockProducts;

    public function __construct()
    {
        $this->stockProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getImageLink(): ?string
    {
        return $this->image_link;
    }

    public function setImageLink(string $image_link): self
    {
        $this->image_link = $image_link;

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
            $stockProduct->setProduct($this);
        }

        return $this;
    }

    public function removeStockProduct(ListsProducts $stockProduct): self
    {
        if ($this->stockProducts->removeElement($stockProduct)) {
            // set the owning side to null (unless already changed)
            if ($stockProduct->getProduct() === $this) {
                $stockProduct->setProduct(null);
            }
        }

        return $this;
    }
}
