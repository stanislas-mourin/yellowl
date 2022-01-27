<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     * @Groups("comments:read")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="idPost")
     * @Groups("comments:read")
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\GreaterThan(0)
     */
    private $idPost;

    /**
     * @ORM\Column(type="integer", name="idUser")
     * @Groups("comments:read")
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\GreaterThan(0)
     */
    private $idUser;

    /**
     * @ORM\Column(type="string", length=255, name="content")
     * @Groups("comments:read")
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", name="dateOfComment")
     * @Groups("comments:read")
     */
    private $dateOfComment;

    /**
     * @ORM\Column(type="integer", name="likes")
     * @Groups("comments:read")
     */
    private $likes;

    /**
     * @ORM\Column(type="integer", name="dislikes")
     * @Groups("comments:read")
     */
    private $dislikes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPost(): ?int
    {
        return $this->idPost;
    }

    public function setIdPost(int $idPost): self
    {
        $this->idPost = $idPost;

        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDateOfComment(): ?\DateTimeInterface
    {
        return $this->dateOfComment;
    }

    public function setDateOfComment(\DateTimeInterface $dateOfComment): self
    {
        $this->dateOfComment = $dateOfComment;

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
}
