<?php

namespace App\Entity;

use App\Repository\ContactRequestRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRequestRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ContactRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'contactRequests')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['json_create'])]
    private ?ContactUser $contactUser = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(
        message: 'Demande inexistante',
    )]
    #[Groups(['json_create'])]
    private string $message;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(options: ["default" => false])]
    #[Groups(['json_create'])]
    private ?bool $checked = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function getContactUser(): ?ContactUser
    {
        return $this->contactUser;
    }

    public function setContactUser(?ContactUser $contactUser): static
    {
        $this->contactUser = $contactUser;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): static
    {
        $this->checked = $checked;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
