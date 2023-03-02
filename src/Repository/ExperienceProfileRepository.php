<?php

namespace App\Repository;

use App\Entity\ExperienceProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExperienceProfile>
 *
 * @method ExperienceProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExperienceProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExperienceProfile[]    findAll()
 * @method ExperienceProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExperienceProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExperienceProfile::class);
    }

    public function save(ExperienceProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExperienceProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return ExperienceProfile[] Returns an array of ExperienceProfile objects
    */
   public function findByProfile($idProfile): array
   {
       return $this->createQueryBuilder('experienceProfile')
           ->andWhere('experienceProfile.profile = :idProfile')
           ->setParameter('idProfile', $idProfile)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?ExperienceProfile
//    {
//        return $this->createQueryBuilder('experienceProfile')
//            ->andWhere('experienceProfile.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
