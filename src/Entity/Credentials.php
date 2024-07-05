<?php

namespace App\Entity;

use App\Repository\CredentialsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CredentialsRepository::class)]
class Credentials
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'credentials')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'credentials')]
    private ?Plugin $plugin = null;

    #[ORM\Column(type: Types::OBJECT)]
    private ?object $credentials = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPlugin(): ?Plugin
    {
        return $this->plugin;
    }

    public function setPlugin(?Plugin $plugin): static
    {
        $this->plugin = $plugin;

        return $this;
    }

    public function getCredentials(): ?object
    {
        return $this->credentials;
    }

    public function setCredentials(object $credentials): static
    {
        $this->credentials = $credentials;

        return $this;
    }
}
