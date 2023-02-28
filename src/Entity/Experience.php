<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(["getExperiences", "getCandidates"])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getExperiences", "getCandidates"])]
    private ?string $positionTitle = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getExperiences", "getCandidates"])]
    private ?string $description = null;

    #[ORM\Column(length: 30)]
    #[Groups(["getExperiences", "getCandidates"])]
    private ?string $sector = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["getExperiences", "getCandidates"])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["getExperiences", "getCandidates"])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    #[Groups(["getExperiences", "getCandidates"])]
    private ?bool $isCurrentPosition = null;

    #[ORM\ManyToOne(inversedBy: 'experiences')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getExperiences"])]
    private ?Candidate $candidate = null;

    #[ORM\OneToMany(mappedBy: 'experience', targetEntity: ExperienceProfile::class)]
    private Collection $experienceProfiles;

    public function __construct()
    {
        $this->experienceProfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isIsCurrentPosition(): ?bool
    {
        return $this->isCurrentPosition;
    }

    public function setIsCurrentPosition(bool $isCurrentPosition): self
    {
        $this->isCurrentPosition = $isCurrentPosition;

        return $this;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidate $candidate): self
    {
        $this->candidate = $candidate;

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
            $experienceProfile->setExperience($this);
        }

        return $this;
    }

    public function removeExperienceProfile(ExperienceProfile $experienceProfile): self
    {
        if ($this->experienceProfiles->removeElement($experienceProfile)) {
            // set the owning side to null (unless already changed)
            if ($experienceProfile->getExperience() === $this) {
                $experienceProfile->setExperience(null);
            }
        }

        return $this;
    }
}
