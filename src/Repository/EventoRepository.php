<?php

namespace App\Repository;

use App\Entity\Evento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use \Datetime;

/**
 * @method Evento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evento[]    findAll()
 * @method Evento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evento::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Evento $entity, bool $flush = true): void
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
    public function remove(Evento $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Evento[] Returns an array of Evento objects
     */
    
    public function findByType($type)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.tipo_evento = :type')
            ->setParameter('type', $type)
            ->orderBy('e.fecha', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return Evento[] Returns an array of Evento objects
     */
    
    public function findByMonth($month)
    {
        $actual_year = (new DateTime)->format("Y");
        return $this->createQueryBuilder('e')
            ->andWhere('MONTH(fecha) = :month and YEAR(fecha) = :actual_year')
            ->setParameter('month', $month)
            ->setParameter('actual_year', $actual_year)
            ->orderBy('e.fecha', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return Evento[] Returns an array of Evento objects
     */
    
    public function findByMonthAndType($month, $type)
    {
        $actual_year = (new DateTime)->format("Y");
        return $this->createQueryBuilder('e')
            ->andWhere('MONTH(fecha) = :month and YEAR(fecha) = :actual_year and e.type = :type')
            ->setParameter('month', $month)
            ->setParameter('actual_year', $actual_year)
            ->setParameter('type', $type)
            ->orderBy('e.fecha', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    
}
