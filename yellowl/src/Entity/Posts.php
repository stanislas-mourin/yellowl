<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostsRepository::class)
 */
class Posts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     * @Groups("posts:read")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="idUser")
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\GreaterThan(0)
     * @Groups("posts:read")
     */
    private $idUser;

    /**
     * @ORM\Column(type="integer", name="idCategory")
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\GreaterThan(0)
     * @Groups("posts:read")
     */
    private $idCategory;

    /**
     * @ORM\Column(type="string", length=255, name="media")
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Groups("posts:read")
     */
    private $media;

    /**
     * @ORM\Column(type="string", length=255, name="title")
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Groups("posts:read")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, name="content")
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Groups("posts:read")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", name="dateOfPost")
     * @Groups({"posts:read", "post:date"})
     */
    private $dateOfPost;

    /**
     * @ORM\Column(type="integer", name="likes")
     * @Groups({"posts:read", "post:like"})
     */
    private $likes;

    /**
     * @ORM\Column(type="integer", name="dislikes")
     * @Groups({"posts:read", "post:dislike"})
     */
    private $dislikes;

    /**
     * @ORM\Column(type="integer", name="reported")
     * @Groups("posts:read")
     */
    private $reported;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdCategory(): ?int
    {
        return $this->idCategory;
    }

    public function setIdCategory(int $idCategory): self
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDateOfPost(): ?\DateTimeInterface
    {
        return $this->dateOfPost;
    }

    public function setDateOfPost(\DateTimeInterface $dateOfPost): self
    {
        $this->dateOfPost = $dateOfPost;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }

    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    public function getReported(): ?int
    {
        return $this->reported;
    }

    public function setReported(int $reported): self
    {
        $this->reported = $reported;

        return $this;
    }
}
