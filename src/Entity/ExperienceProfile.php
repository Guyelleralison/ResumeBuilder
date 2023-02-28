<?php

namespace App\Entity;

use App\Repository\ExperienceProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienceProfileRepository::class)]
class ExperienceProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idProfile = null;

    #[ORM\Column]
    private ?int $idExperience = null;

    #[ORM\ManyToOne(inversedBy: 'experienceProfiles')]
    private ?Profile $profile = null;

    #[ORM\ManyToOne(inversedBy: 'experienceProfiles')]
    private ?Experience $experience = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProfile(): ?int
    {
        return $this->idProfile;
    }

    public function setIdProfile(int $idProfile): self
    {
        $this->idProfile = $idProfile;

        return $this;
    }

    public function getIdExperience(): ?int
    {
        return $this->idExperience;
    }

    public function setIdExperience(int $idExperience): self
    {
        $this->idExperience = $idExperience;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): self
    {
        $this->experience = $experience;

        return $this;
    }
}
