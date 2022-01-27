<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CategoriesRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Categories
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 * 
 */
class Categories
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nameCategory", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Valeur erronnée")
     */
    private $namecategory = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=65535, nullable=false)
     * @Assert\NotBlank(message="Valeur erronnée")
     */
    private $avatar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamecategory(): ?string
    {
        return $this->namecategory;
    }

    public function setNamecategory(string $namecategory): self
    {
        $this->namecategory = $namecategory;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }


}