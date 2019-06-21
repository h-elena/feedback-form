<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Feedback;

/**
 * @method Feedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedback[]    findAll()
 * @method Feedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    /**
     * @return Feedback[] Returns an array
     */
    public function findByIpOrEmailOnTime()
    {
        $qb = $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->andWhere('f.ip = :ip')
            ->andWhere('f.date_create > :last')
            ->setParameter('ip', $_SERVER['REMOTE_ADDR'])
            ->setParameter('last', new \DateTime('-2 minute'), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getQuery();

        return $qb->getSingleScalarResult();
    }

    public function findByInsertOnTime()
    {
        $qb = $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->andWhere('f.date_create > :last')
            ->setParameter('last', new \DateTime('-2 minute'), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getQuery();

        return $qb->getSingleScalarResult();
    }
}
