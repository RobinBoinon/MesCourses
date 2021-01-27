<?php

namespace App\Entity;

use App\Repository\ListsProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListsProductsRepository::class)
 */
class ListsProducts
{
  /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Lists", inversedBy="ListsProducts")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="id", nullable=false)
     */
    protected $lists;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="ListsProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    protected $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getLists(): ?Lists
    {
        return $this->lists;
    }

    public function setLists(?Lists $lists): self
    {
        $this->lists = $lists;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
