<?php

namespace App\Entity;

use App\Repository\CandidateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
class Candidate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCandidates", "getExperiences", "getExperiencesProfile"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getCandidates", "getExperiences"])]
    private ?string $lastName = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getCandidates", "getExperiences"])]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["getCandidates"])]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 15)]
    #[Groups(["getCandidates"])]
    private ?string $gender = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getCandidates"])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(["getCandidates"])]
    private ?int $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["getCandidates"])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: Experience::class, orphanRemoval: true)]
    #[Groups(["getCandidates"])]
    private Collection $experiences;

    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: Career::class)]
    private Collection $careers;

    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: Formation::class)]
    private Collection $formations;

    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: CandidateSkill::class)]
    private Collection $candidateSkills;

    #[ORM\Column(length: 20)]
    #[Groups(["getCandidates"])]
    private ?string $refCandidate = null;

    #[ORM\Column(length: 30)]
    #[Groups(["getCandidates"])]
    private ?string $matricule = null;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
        $this->careers = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->candidateSkills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setCandidate($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getCandidate() === $this) {
                $experience->setCandidate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Career>
     */
    public function getCareers(): Collection
    {
        return $this->careers;
    }

    public function addCareer(Career $career): self
    {
        if (!$this->careers->contains($career)) {
            $this->careers->add($career);
            $career->setCandidate($this);
        }

        return $this;
    }

    public function removeCareer(Career $career): self
    {
        if ($this->careers->removeElement($career)) {
            // set the owning side to null (unless already changed)
            if ($career->getCandidate() === $this) {
                $career->setCandidate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setCandidate($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getCandidate() === $this) {
                $formation->setCandidate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CandidateSkill>
     */
    public function getCandidateSkills(): Collection
    {
        return $this->candidateSkills;
    }

    public function addCandidateSkill(CandidateSkill $candidateSkill): self
    {
        if (!$this->candidateSkills->contains($candidateSkill)) {
            $this->candidateSkills->add($candidateSkill);
            $candidateSkill->setCandidate($this);
        }

        return $this;
    }

    public function removeCandidateSkill(CandidateSkill $candidateSkill): self
    {
        if ($this->candidateSkills->removeElement($candidateSkill)) {
            // set the owning side to null (unless already changed)
            if ($candidateSkill->getCandidate() === $this) {
                $candidateSkill->setCandidate(null);
            }
        }

        return $this;
    }

    public function getRefCandidate(): ?string
    {
        return $this->refCandidate;
    }

    public function setRefCandidate(string $refCandidate): self
    {
        $this->refCandidate = $refCandidate;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }
}
