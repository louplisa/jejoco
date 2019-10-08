<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email")
 */
class User implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */

    private $lastname;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @Assert\Email()
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registered_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Organization", cascade={"persist", "remove"}, mappedBy="user", orphanRemoval=true)
     */
    protected $organizations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AssoEvent", cascade={"persist", "remove"}, mappedBy="user", orphanRemoval=true)
     */
    protected $asso_events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BelongTo", mappedBy="user", orphanRemoval=true, cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $belongTos;

    private $roles = [];

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
        $this->belongTos = new ArrayCollection();
        $this->asso_events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getRegisteredAt(): ?DateTimeInterface
    {
        return $this->registered_at;
    }

    public function setRegisteredAt(DateTimeInterface $registered_at): self
    {
        $this->registered_at = $registered_at;

        return $this;
    }

    function getPlainPassword()
    {
        return $this->password;
    }

    function setPlainPassword($password)
    {
        $this->password = $password;
    }

    public function addOrganization(Organization $organization)
    {
        $this->organizations->add($organization);
        $organization->setUser($this);
    }

    public function getOrganization()
    {
        $organizations = new ArrayCollection();

        return $organizations;
    }

    /**
     * @return mixed
     */
    public function getAssoEvent()
    {
        $asso_events = new ArrayCollection();
        return $asso_events;
    }

    public function addAssoEvents( AssoEvent $asso_event)
    {
        $this->asso_events->add($asso_event);
        $asso_event->setUser($this);
    }

    /**
     * @return mixed
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

    /**
     * @return Collection|BelongTo[]
     */
    public function getBelongTos(): Collection
    {
        return $this->belongTos;
    }

    /**
     * @param BelongTo $belongTo
     * @return User|null
     */
    public function addBelongTo(BelongTo $belongTo): self
    {
        if (!$this->belongTos->contains($belongTo)) {
            $this->belongTos[] = $belongTo;
            $belongTo->setUser($this);
        }

        return $this;
    }

    public function removeBelongTo(BelongTo $belongTo): self
    {
        if ($this->belongTos->contains($belongTo)) {
            $this->belongTos->removeElement($belongTo);
            // set the owning side to null (unless already changed)
            if ($belongTo->getUser() === $this) {
                $belongTo->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return;
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->getOrganizations()
        ]);
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->organizations
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}
