<?php

namespace App\Repository;

use App\Entity\Projects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Projects>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projects::class);
    }

    public function findAllSorted(string $sortField, string $sortDirection): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.device', 'd')
            ->leftJoin('p.uploadedBy', 'u')
            ->addSelect('d', 'u');

        switch ($sortField) {
            case 'device':
                $queryBuilder->orderBy('d.name', $sortDirection);
                break;
            case 'uploadedBy':
                $queryBuilder->orderBy('u.username', $sortDirection);
                break;
            default:
                $queryBuilder->orderBy('p.name', $sortDirection);
                break;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Projects[] Returns an array of Projects objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Projects
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
