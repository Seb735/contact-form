<?php

namespace App\Entity;

use App\Repository\ContactUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContactUserRepository::class)]
class ContactUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['json_create'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['json_create'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['json_create'])]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'contactUser', targetEntity: ContactRequest::class, orphanRemoval: true)]
    private Collection $contactRequests;

    public function __construct()
    {
        $this->contactRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, ContactRequest>
     */
    public function getContactRequests(): Collection
    {
        return $this->contactRequests;
    }

    public function addContactRequest(ContactRequest $contactRequest): static
    {
        if (!$this->contactRequests->contains($contactRequest)) {
            $this->contactRequests->add($contactRequest);
            $contactRequest->setContactUser($this);
        }

        return $this;
    }

    public function removeContactRequest(ContactRequest $contactRequest): static
    {
        if ($this->contactRequests->removeElement($contactRequest)) {
            // set the owning side to null (unless already changed)
            if ($contactRequest->getContactUser() === $this) {
                $contactRequest->setContactUser(null);
            }
        }

        return $this;
    }
}
