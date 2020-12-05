<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(
     *      min = 3,
     *      max = 180,
     *      minMessage = "L'identifiant doit comporter plus de {{ limit }} caractères",
     *      maxMessage = "L'identifiant ne doit pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Le mot de passe doit comporter plus de {{ limit }} caractères",
     *      maxMessage = "Le mot de passe ne doit pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToOne(targetEntity=Media::class, cascade={"persist", "remove"})
     */
    private $media;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="createdBy", orphanRemoval=true)
     */
    private $myTickets;

    /**
     * @ORM\ManyToMany(targetEntity=Ticket::class, mappedBy="developers")
     */
    private $assignedTickets;

    public function __construct()
    {
        $this->myTickets = new ArrayCollection();
        $this->assignedTickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getFirstname().' '.$this->getLastname();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getMyTickets(): Collection
    {
        return $this->myTickets;
    }

    public function addMyTicket(Ticket $myTicket): self
    {
        if (!$this->myTickets->contains($myTicket)) {
            $this->myTickets[] = $myTicket;
            $myTicket->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMyTicket(Ticket $myTicket): self
    {
        if ($this->myTickets->removeElement($myTicket)) {
            // set the owning side to null (unless already changed)
            if ($myTicket->getCreatedBy() === $this) {
                $myTicket->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getAssignedTickets(): Collection
    {
        return $this->assignedTickets;
    }

    public function addAssignedTicket(Ticket $assignedTicket): self
    {
        if (!$this->assignedTickets->contains($assignedTicket)) {
            $this->assignedTickets[] = $assignedTicket;
            $assignedTicket->addDeveloper($this);
        }

        return $this;
    }

    public function removeAssignedTicket(Ticket $assignedTicket): self
    {
        if ($this->assignedTickets->removeElement($assignedTicket)) {
            $assignedTicket->removeDeveloper($this);
        }

        return $this;
    }
}
