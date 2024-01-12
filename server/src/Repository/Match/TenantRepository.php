<?php

namespace App\Repository\Match;

use App\Entity\Match\Tenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Tenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tenant[]    findAll()
 * @method Tenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tenant::class);
    }

    /**
     * @return Tenant[]
     */
    public function findTenantByUserEmail(string $email): array
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.email = :email')
            ->setParameter('email', $email);

        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * @return Tenant[]
     */
    public function findTenantByUserId(string $userId): Tenant
    {
        $qb = $this->createQueryBuilder('t')
            ->join('t.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

}