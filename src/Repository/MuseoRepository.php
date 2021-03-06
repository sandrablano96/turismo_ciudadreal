<?php

namespace App\Repository;

use App\Entity\Museo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Museos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Museos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Museos[]    findAll()
 * @method Museos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MuseoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Museo::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Museo $entity, bool $flush = true): void
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
    public function remove(Museo $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Museos[] Returns an array of Museos objects
     */
    
    public function findAllDesc() : array
    {
        return $this->createQueryBuilder('m')
            ->addOrderBy('m.nombre', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return Museos[] Returns an array of Museos objects
     */
    
    public function findAllAsc() : array
    {
        return $this->createQueryBuilder('m')
            ->addOrderBy('m.nombre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Museos
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}