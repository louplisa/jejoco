<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssoEventRepository")
 *  @Vich\Uploadable()
 */
class AssoEvent
{
    const DATE_FORMAT = 'd-m-Y\\TH:i:s.u\\Z';

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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $beginAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eventPictureOne;

    /**
     * var File|null
     * @Assert\Image(mimeTypes="image/jpeg")
     * @Vich\UploadableField(mapping="eventAsso_image", fileNameProperty="eventPictureOne")
     */
    private $imageFileOne;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedOne_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eventPictureTwo;

    /**
     * var File|null
     * @Assert\Image(mimeTypes="image/jpeg")
     * @Vich\UploadableField(mapping="eventAsso_image", fileNameProperty="eventPictureTwo")
     */

    private $imageFileTwo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedTwo_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eventPictureThree;

    /**
     * var File|null
     * @Assert\Image(mimeTypes="image/jpeg")
     * @Vich\UploadableField(mapping="eventAsso_image", fileNameProperty="eventPictureThree")
     */
    private $imageFileThree;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedThree_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization", inversedBy="asso_events")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id" )
     */
    protected $organization;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="asso_events")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Organization",  inversedBy="eventsPartner", orphanRemoval=true)
     * @ORM\JoinTable(name="organization_partner_relations")
     */
    protected $organizationsPartner;

    public function __construct()
    {
        $this->organizationsPartner = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBeginAt()
    {
        return $this->beginAt;
    }

    /**
     * @param mixed $beginAt
     */
    public function setBeginAt($beginAt): void
    {
        $this->beginAt = $beginAt;
    }

    /**
     * @return mixed
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param mixed $endAt
     */
    public function setEndAt($endAt): void
    {
        $this->endAt = $endAt;
    }


    public function getEventPictureOne(): ?string
    {
        return $this->eventPictureOne;
    }

    public function setEventPictureOne(?string $eventPictureOne): self
    {
        $this->eventPictureOne = $eventPictureOne;

        return $this;
    }

    public function getEventPictureTwo(): ?string
    {
        return $this->eventPictureTwo;
    }

    public function setEventPictureTwo(?string $eventPictureTwo): self
    {
        $this->eventPictureTwo = $eventPictureTwo;

        return $this;
    }

    public function getEventPictureThree(): ?string
    {
        return $this->eventPictureThree;
    }

    public function setEventPictureThree(?string $eventPictureThree): self
    {
        $this->eventPictureThree = $eventPictureThree;

        return $this;
    }

    public function getUpdatedOneAt(): ?DateTimeInterface
    {
        return $this->updatedOne_at;
    }

    public function setUpdatedOneAt(?DateTimeInterface $updatedOne_at): self
    {
        $this->updatedOne_at = $updatedOne_at;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedTwoAt(): ?DateTimeInterface
    {
        return $this->updatedTwo_at;
    }

    public function setUpdatedTwoAt(?DateTimeInterface $updatedTwo_at): self
    {
        $this->updatedTwo_at = $updatedTwo_at;
        return $this;
    }

    public function getUpdatedThreeAt(): ?DateTimeInterface
    {
        return $this->updatedThree_at;
    }

    public function setUpdatedThreeAt(?DateTimeInterface $updatedThree_at): self
    {
        $this->updatedThree_at = $updatedThree_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFileOne()
    {
        return $this->imageFileOne;
    }

    /**
     * @param mixed $imageFileOne
     * @return AssoEvent
     * @throws Exception
     */
    public function setImageFileOne(?File $imageFileOne): AssoEvent
    {
        $this->imageFileOne = $imageFileOne;
        if ($this->imageFileOne instanceof UploadedFile) {
            $this->updatedOne_at = new DateTime('now');
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFileTwo()
    {
        return $this->imageFileTwo;
    }

    /**
     * @param mixed $imageFileTwo
     * @return AssoEvent
     * @throws Exception
     */
    public function setImageFileTwo(?File $imageFileTwo): AssoEvent
    {
        $this->imageFileTwo = $imageFileTwo;
        if ($this->imageFileTwo instanceof UploadedFile) {
            $this->updatedTwo_at = new DateTime('now');
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFileThree()
    {
        return $this->imageFileThree;
    }

    /**
     * @param mixed $imageFileThree
     * @return AssoEvent
     * @throws Exception
     */
    public function setImageFileThree(?File $imageFileThree): AssoEvent
    {
        $this->imageFileThree = $imageFileThree;
        if ($this->imageFileThree instanceof UploadedFile) {
            $this->updatedThree_at = new DateTime('now');
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization): void
    {
        $this->organization = $organization;
    }

    public function getOrganizationsPartner(): Collection
    {
        return $this->organizationsPartner;
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

    public function addOrganizationPartner(Organization $organizationPartner): self
    {
        if (!$this->organizationsPartner->contains($organizationPartner)) {
            $this->organizationsPartner [] = $organizationPartner;
            $organizationPartner->addEventPartner($this);
        }
        return $this;
    }
}

