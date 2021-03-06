<?php

namespace App\Repository;

use App\Entity\VisitaGuiada;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VisitasGuiadas|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisitasGuiadas|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisitasGuiadas[]    findAll()
 * @method VisitasGuiadas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitaGuiadaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisitaGuiada::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(VisitaGuiada $entity, bool $flush = true): void
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
    public function remove(VisitaGuiada $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    
    public function findOneByUid($value): ?VisitaGuiada
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.uid = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
