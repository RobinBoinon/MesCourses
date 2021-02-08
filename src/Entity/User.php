<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $hash_key;

    /** @ORM\OneToMany(targetEntity="Lists", mappedBy="user") */
    protected $list;

    public function __construct()
    {
        $this->list = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getHashKey(): ?string
    {
        return $this->hash_key;
    }

    public function setHashKey(string $hash_key): self
    {
        $this->hash_key = $hash_key;

        return $this;
    }

    /**
     * @return Collection|Lists[]
     */
    public function getList(): Collection
    {
        return $this->list;
    }

    public function addList(Lists $list): self
    {
        if (!$this->list->contains($list)) {
            $this->list[] = $list;
            $list->setUser($this);
        }

        return $this;
    }

    public function removeList(Lists $list): self
    {
        if ($this->list->removeElement($list)) {
            // set the owning side to null (unless already changed)
            if ($list->getUser() === $this) {
                $list->setUser(null);
            }
        }

        return $this;
    }
}
