<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=TicketPriority::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticketPriority;

    /**
     * @ORM\ManyToOne(targetEntity=TicketType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticketType;

    /**
     * @ORM\OneToOne(targetEntity=Media::class, cascade={"persist", "remove"})
     */
    private $media;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="myTickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity=TicketLog::class, mappedBy="ticket", orphanRemoval=true)
     */
    private $ticketLogs;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="assignedTickets")
     */
    private $developers;

    public function __construct()
    {
        $this->ticketLogs = new ArrayCollection();
        $this->developers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTicketPriority(): ?TicketPriority
    {
        return $this->ticketPriority;
    }

    public function setTicketPriority(?TicketPriority $ticketPriority): self
    {
        $this->ticketPriority = $ticketPriority;

        return $this;
    }

    public function getTicketType(): ?TicketType
    {
        return $this->ticketType;
    }

    public function setTicketType(?TicketType $ticketType): self
    {
        $this->ticketType = $ticketType;

        return $this;
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|TicketLog[]
     */
    public function getTicketLogs(): Collection
    {
        return $this->ticketLogs;
    }

    public function addTicketLog(TicketLog $ticketLog): self
    {
        if (!$this->ticketLogs->contains($ticketLog)) {
            $this->ticketLogs[] = $ticketLog;
            $ticketLog->setTicket($this);
        }

        return $this;
    }

    public function removeTicketLog(TicketLog $ticketLog): self
    {
        if ($this->ticketLogs->removeElement($ticketLog)) {
            // set the owning side to null (unless already changed)
            if ($ticketLog->getTicket() === $this) {
                $ticketLog->setTicket(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getDevelopers(): Collection
    {
        return $this->developers;
    }

    public function addDeveloper(User $developer): self
    {
        if (!$this->developers->contains($developer)) {
            $this->developers[] = $developer;
        }

        return $this;
    }

    public function removeDeveloper(User $developer): self
    {
        $this->developers->removeElement($developer);

        return $this;
    }
}
