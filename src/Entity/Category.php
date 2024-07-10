<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NAME', fields: ['name'])]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Plugin>
     */
    #[ORM\OneToMany(targetEntity: Plugin::class, mappedBy: 'category_id')]
    private Collection $plugins;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->plugins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Plugin>
     */
    public function getPlugins(): Collection
    {
        return $this->plugins;
    }

    public function addPlugin(Plugin $plugin): static
    {
        if (!$this->plugins->contains($plugin)) {
            $this->plugins->add($plugin);
            $plugin->setCategoryId($this);
        }

        return $this;
    }

    public function removePlugin(Plugin $plugin): static
    {
        if ($this->plugins->removeElement($plugin)) {
            // set the owning side to null (unless already changed)
            if ($plugin->getCategoryId() === $this) {
                $plugin->setCategoryId(null);
            }
        }

        return $this;
    }
}
