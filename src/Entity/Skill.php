<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getSkills"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[Groups(["getSkills"])]
    private ?SkillCategory $category = null;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[Groups(["getSkills"])]
    private ?SkillTechnology $technology = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?SkillCategory
    {
        return $this->category;
    }

    public function setCategory(?SkillCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getTechnology(): ?SkillTechnology
    {
        return $this->technology;
    }

    public function setTechnology(?SkillTechnology $technology): self
    {
        $this->technology = $technology;

        return $this;
    }
}
