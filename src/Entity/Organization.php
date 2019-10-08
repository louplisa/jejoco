<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganizationRepository")
 * @UniqueEntity(fields="name")
 * @Vich\Uploadable()
 */
class Organization
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $organization_number;

    /**
     * @Assert\Email()
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address_headquarters;

    /**
     * @Assert\Regex("/^[0-9]{5}$/")
     * @ORM\Column(type="string", length=255)
     */
    private $zip_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $president_lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $president_firstname;

    /**
     *  @Assert\Regex(
     *  pattern="/[0-9]{10}/"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_fix;

    /**
     *  @Assert\Regex(
     *  pattern="/[0-9]{10}/"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_mobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var File|null
     * @Assert\Image(mimeTypes="image/jpeg")
     * @Vich\UploadableField(mapping="association_image", fileNameProperty="logo")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime|null
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registered_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="organizations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id" )
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AssoEvent", cascade={"persist", "remove"}, mappedBy="organization", orphanRemoval=true)
     */
    protected $asso_events;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AssoEvent", mappedBy="organizationsPartner", orphanRemoval=true)
     * @ORM\JoinTable(name="organization_partner_relations")
     */
    protected $eventsPartner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BelongTo", mappedBy="organizations", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $belongTos;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->asso_events = new ArrayCollection();
        $this->eventsPartner =new ArrayCollection();
        $this->belongTos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name . '(' . $this->id .')';
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

    public function getOrganizationNumber(): ?string
    {
        return $this->organization_number;
    }

    public function setOrganizationNumber(?string $organization_number): self
    {
        $this->organization_number = $organization_number;

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

    public function getAddressHeadquarters(): ?string
    {
        return $this->address_headquarters;
    }

    public function setAddressHeadquarters(string $address_headquarters): self
    {
        $this->address_headquarters = $address_headquarters;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): self
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPresidentLastname(): ?string
    {
        return $this->president_lastname;
    }

    public function setPresidentLastname(string $president_lastname): self
    {
        $this->president_lastname = $president_lastname;

        return $this;
    }

    public function getPresidentFirstname(): ?string
    {
        return $this->president_firstname;
    }

    public function setPresidentFirstname(string $president_firstname): self
    {
        $this->president_firstname = $president_firstname;

        return $this;
    }

    public function getPhoneFix(): ?string
    {
        return $this->phone_fix;
    }

    public function setPhoneFix(?string $phone_fix): self
    {
        $this->phone_fix = $phone_fix;

        return $this;
    }

    public function getPhoneMobile(): ?string
    {
        return $this->phone_mobile;
    }

    public function setPhoneMobile(?string $phone_mobile): self
    {
        $this->phone_mobile = $phone_mobile;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getAssoEvents()
    {
        return $this->asso_events;
    }

    public function addAssoEvents( AssoEvent $asso_event)
    {
        $this->asso_events->add($asso_event);
        $asso_event->setOrganization($this);
    }

    /**
     * @return mixed
     */
    public function getEventsPartner(): Collection
    {
        return $this->eventsPartner;
    }


    public function addEventPartner(AssoEvent $eventPartner):self
    {
        if(!$this->eventsPartner->contains($eventPartner)){
            $this->eventsPartner[]= $eventPartner;
            $eventPartner->addOrganizationPartner($this);
        }
        return $this;
    }


    /**
     * @param $eventsPartner
     */
    public function setEventsPartner($eventsPartner): void
    {
        $this->eventsPartner = $eventsPartner;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     * @return Organization
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|UploadedFile $imageFile
     * @return Organization
     * @throws Exception
     */
    public function setImageFile(?File $imageFile): Organization
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new DateTime('now');
        }
        return $this;
    }

    /**
     * @return Collection|BelongTo[]
     */
    public function getBelongTos(): Collection
    {
        return $this->belongTos;
    }

    public function addBelongTo(BelongTo $belongTo): self
    {
        if (!$this->belongTos->contains($belongTo)) {
            $this->belongTos[] = $belongTo;
            $belongTo->setOrganizations($this);
        }

        return $this;
    }

    public function removeBelongTo(BelongTo $belongTo): self
    {
        if ($this->belongTos->contains($belongTo)) {
            $this->belongTos->removeElement($belongTo);
            // set the owning side to null (unless already changed)
            if ($belongTo->getOrganizations() === $this) {
                $belongTo->setOrganizations(null);
            }
        }

        return $this;
    }
}
