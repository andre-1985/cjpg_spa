<?php

namespace App\Repository;

use App\Entity\UserSelectionLoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserSelectionLoto>
 *
 * @method UserSelectionLoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSelectionLoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSelectionLoto[]    findAll()
 * @method UserSelectionLoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSelectionLotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSelectionLoto::class);
    }

    public function save(UserSelectionLoto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserSelectionLoto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UserSelectionLoto[] Returns an array of UserSelectionLoto objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserSelectionLoto
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
