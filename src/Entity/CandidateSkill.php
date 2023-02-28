<?php

namespace App\Entity;

use App\Repository\CandidateSkillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidateSkillRepository::class)]
class CandidateSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idSkill = null;

    #[ORM\Column]
    private ?int $idCandidate = null;

    #[ORM\ManyToOne(inversedBy: 'candidateSkills')]
    private ?Candidate $candidate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSkill(): ?int
    {
        return $this->idSkill;
    }

    public function setIdSkill(int $idSkill): self
    {
        $this->idSkill = $idSkill;

        return $this;
    }

    public function getIdCandidate(): ?int
    {
        return $this->idCandidate;
    }

    public function setIdCandidate(int $idCandidate): self
    {
        $this->idCandidate = $idCandidate;

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
}
