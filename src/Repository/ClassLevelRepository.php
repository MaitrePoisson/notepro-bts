<?php

namespace App\Repository;

use App\Entity\ClassLevel;
use App\Entity\Professor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClassLevel>
 *
 * @method ClassLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassLevel[]    findAll()
 * @method ClassLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassLevel::class);
    }

    public function findByProfessor(Professor $professor)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.professors', 'p')
            ->andWhere('p.id = :professor')
            ->setParameter('professor', $professor);
    }

//    /**
//     * @return ClassLevel[] Returns an array of ClassLevel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClassLevel
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
