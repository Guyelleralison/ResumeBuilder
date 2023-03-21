<?php

namespace App\Entity;

use App\Repository\SkillTechnologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillTechnologyRepository::class)]
class SkillTechnology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getTechnology"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getTechnology"])]
    private ?string $name = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(["getTechnology"])]
    private ?string $version = null;

    #[ORM\OneToMany(mappedBy: 'technology', targetEntity: Skill::class)]
    private Collection $skills;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->setTechnology($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getTechnology() === $this) {
                $skill->setTechnology(null);
            }
        }

        return $this;
    }
}
