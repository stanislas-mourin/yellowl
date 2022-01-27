<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

header("Access-Control-Allow-Origin: * ");

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     * @Groups("users:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="lastName")
     * @Groups("users:read")
     * @Assert\NotBlank(message="Le nom de famille doit être renseigné")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, name="firstName")
     * @Assert\NotBlank(message="Le prénom doit être renseigné")
     * @Groups("users:read")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, name="nickName")
     * @Assert\NotBlank(message="Le pseudo doit être renseigné")
     * @Groups("users:read")
     */
    private $nickName;

    /**
     * @ORM\Column(type="string", length=255, name="emailAdress")
     * @Assert\NotBlank(message="L'adresse e-mail doit être renseignée")
     * @Groups("users:read")
     */
    private $emailAddress;

    /**
     * @ORM\Column(type="date", name="dateOfBirth")
     * @Assert\NotBlank(message="La date de naissance doit être renseignée")
     * @Groups("users:read")
     */
    // private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255, name="password")
     * @Assert\NotBlank(message="Le mot de passe doit être renseigné")
     * @Groups("users:read")
     */
    private $password;

    /**
     * @ORM\Column(type="integer", name="isAdmin")
     * @Assert\NotBlank(message="Le champ isAdmin doit être renseigné")
     * @Groups("users:read")
     */
    private $isAdmin;

    /**
     * @ORM\Column(type="string", length=255, name="preferences")
     * @Assert\NotBlank(message="Les préférences doivent être renseignées")
     * @Groups("users:read")
     */
    private $preferences;

    /**
     * @ORM\Column(type="string", name="avatar")
     * @Groups("users:read")
     */
    private $avatar;
    
    public function getId()
    {
        return $this->id;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getNickName()
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName)
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTime $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(int $isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getPreferences()
    {
        return $this->preferences;
    }

    public function setPreferences(string $preferences)
    {
        $this->preferences = $preferences;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }
}
