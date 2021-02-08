<?php

namespace App\Entity;

use App\Repository\ListsProductsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ListsProductsRepository::class)
 */
class ListsProducts
{
  /**
     * @Groups("listsproducts")
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Lists", inversedBy="ListsProducts")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="id", nullable=false)
     */
    protected $lists;

    /**
     * @Groups("listsproducts")
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="ListsProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    protected $product;

    /**
     * @Groups("listsproducts")
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
