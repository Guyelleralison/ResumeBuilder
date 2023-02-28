<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Candidate;
use App\Entity\Experience;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $candidates = [];
        $candidateIds = [];
        //candidates list
        for ($i = 0; $i < 10 ; $i ++) {
            $candidate = new Candidate;
            $candidate->setLastName('Candidate N-'.$i);
            $candidate->setFirstName('Candidate F-'.$i);
            $candidate->setDateOfBirth(date_create("now"));
            if ($i < 5) {
                $candidate->setGender('Féminin');
                $candidate->setStatus(1);
            } else {
                $candidate->setGender('Masculin');
                $candidate->setStatus(2);
            }
            $candidate->setEmail('recrutment@etechconsulting-mg.com');
            $candidate->setMatricule('10'. $i);
            $candidate->setRefCandidate('NF - 10'. $i);
            $manager->persist($candidate);

            $candidates[] = $candidate;
            $candidateIds[] = $candidate->getId();
        }

        //experiences list
        for ($i = 0; $i < count($candidates); $i ++) {
            $experience = new Experience;
            $experience->setTitle('Experience -'.$candidates[$i]->getId());
            $experience->setPositionTitle('Position Title - '.$candidates[$i]->getId());
            $experience->setDescription('Description Position Title '.$candidates[$i]->getId());
            $experience->setSector('Sector '.$i);
            $experience->setStartDate(date_create("now"));
            $experience->setEndDate(date_create("now"));
            $experience->setIsCurrentPosition(
                $experience->getEndDate() ? false : true
            );
            $experience->setCandidate($candidates[array_rand($candidates)]);
            $manager->persist($experience);
        }

        //skillCategory & skillTechnology
        
        
        $manager->flush();
    }
}
