<?php

namespace App\Repository;

use App\Entity\Dishes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dishes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dishes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dishes[]    findAll()
 * @method Dishes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dishes::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Dishes $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Dishes $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // Make our own Query Bulilder, Like get record which price is less than 5 or anything you want
    public function findFiveEur(int $id)
    {
        // We can also follow the below
        $qb = $this->createQueryBuilder('d');
        $qb->select('d.id, d.price')
        ->where('d.price <= 5');
        return $qb->getQuery()->getResult();
        
    //     return $this
    // ->createQueryBuilder('dis')
    // ->select('dis.id, dis.price')
    // ->andWhere('dis.price <= 5')
    // ->getQuery()
    // ->getResult();
        
    }



    // /**
    //  * @return Dishes[] Returns an array of Dishes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dishes
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
