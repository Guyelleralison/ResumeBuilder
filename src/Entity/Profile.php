<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $positionTitle = null;

    #[ORM\OneToMany(mappedBy: 'profile', targetEntity: ExperienceProfile::class)]
    private Collection $experienceProfiles;

    public function __construct()
    {
        $this->experienceProfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPositionTitle(): ?string
    {
        return $this->positionTitle;
    }

    public function setPositionTitle(string $positionTitle): self
    {
        $this->positionTitle = $positionTitle;

        return $this;
    }

    /**
     * @return Collection<int, ExperienceProfile>
     */
    public function getExperienceProfiles(): Collection
    {
        return $this->experienceProfiles;
    }

    public function addExperienceProfile(ExperienceProfile $experienceProfile): self
    {
        if (!$this->experienceProfiles->contains($experienceProfile)) {
            $this->experienceProfiles->add($experienceProfile);
            $experienceProfile->setProfile($this);
        }

        return $this;
    }

    public function removeExperienceProfile(ExperienceProfile $experienceProfile): self
    {
        if ($this->experienceProfiles->removeElement($experienceProfile)) {
            // set the owning side to null (unless already changed)
            if ($experienceProfile->getProfile() === $this) {
                $experienceProfile->setProfile(null);
            }
        }

        return $this;
    }
}
