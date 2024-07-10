<?php

namespace App\Entity;

use App\Repository\PluginRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PluginRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_DASHBOARD_PATH_NAME', fields: ['dashboard_path', 'name'])]
#[Vich\Uploadable]
class Plugin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'plugin_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $dashboard_path = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $credentials_form_fields = null;

    #[ORM\ManyToOne(inversedBy: 'plugins')]
    private ?Category $category = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, PluginScreenShots>
     */
    #[ORM\OneToMany(targetEntity: PluginScreenShots::class, mappedBy: 'plugin', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $screenShots;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $setup = null;

    /**
     * @var Collection<int, Credentials>
     */
    #[ORM\OneToMany(targetEntity: Credentials::class, mappedBy: 'plugin')]
    private Collection $credentials;

    /**
     * @var Collection<int, Rating>
     */
    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'plugin')]
    private Collection $ratings;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'plugins')]
    private Collection $user;

    

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->screenShots = new ArrayCollection();
        $this->credentials = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->user = new ArrayCollection();
        
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getDashboardPath(): ?string
    {
        return $this->dashboard_path;
    }

    public function setDashboardPath(?string $dashboard_path): static
    {
        $this->dashboard_path = $dashboard_path;

        return $this;
    }

    public function getCredentialsFormFields(): ?array
    {
        return $this->credentials_form_fields;
    }

    public function setCredentialsFormFields(?array $credentials_form_fields): static
    {
        $this->credentials_form_fields = $credentials_form_fields;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
        * @return Collection<int, PluginScreenShots>
        */
    public function getScreenShots(): Collection
    {
        return $this->screenShots;
    }

    public function addScreenShot(PluginScreenShots $screenShot): self
    {
        if (!$this->screenShots->contains($screenShot)) {
            $this->screenShots->add($screenShot);
            $screenShot->setPlugin($this);
        }

        return $this;
    }

    public function removeScreenShot(PluginScreenShots $screenShot): self
    {
        if ($this->screenShots->removeElement($screenShot)) {
            // set the owning side to null (unless already changed)
            if ($screenShot->getPlugin() === $this) {
                $screenShot->setPlugin(null);
            }
        }

        return $this;
    }

    public function getSetup(): ?string
    {
        return $this->setup;
    }

    public function setSetup(?string $setup): static
    {
        $this->setup = $setup;

        return $this;
    }

    /**
     * @return Collection<int, Credentials>
     */
    public function getCredentials(): Collection
    {
        return $this->credentials;
    }

    public function addCredential(Credentials $credential): static
    {
        if (!$this->credentials->contains($credential)) {
            $this->credentials->add($credential);
            $credential->setPlugin($this);
        }

        return $this;
    }

    public function removeCredential(Credentials $credential): static
    {
        if ($this->credentials->removeElement($credential)) {
            // set the owning side to null (unless already changed)
            if ($credential->getPlugin() === $this) {
                $credential->setPlugin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setPlugin($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getPlugin() === $this) {
                $rating->setPlugin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }




}
