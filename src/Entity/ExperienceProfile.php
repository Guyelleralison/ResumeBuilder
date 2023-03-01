<?php

namespace App\Entity;

use App\Repository\ExperienceProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExperienceProfileRepository::class)]
class ExperienceProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getExperiencesProfile", "getProfiles"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'experienceProfiles')]
    #[Groups(["getExperiencesProfile"])]
    private ?Profile $profile = null;

    #[ORM\ManyToOne(inversedBy: 'experienceProfiles')]
    #[Groups(["getExperiencesProfile"])]
    private ?Experience $experience = null;

    public function getId(): ?int
    {
        return $this->id;
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
