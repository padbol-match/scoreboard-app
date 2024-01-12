<?php

namespace App\Repository\Scoreboard;

use App\Entity\Scoreboard\Tenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Match\TenantRepository as MatchTenantRepository;

/**
 * @method Tenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tenant[]    findAll()
 * @method Tenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, MatchTenantRepository $matchTenantRepository)
    {
        parent::__construct($registry, Tenant::class);

        $this->matchTenantRepository = $matchTenantRepository;
    }

    /**
     * @return Tenant
     */
    public function findTenantByUserId(string $userId): Tenant
    {
        $matchTenant = $this->matchTenantRepository->findTenantByUserId($userId);

        if(!is_null($matchTenant)){
            $tenant = $this->findOneByTenant($matchTenant->getId());

            return $tenant;
        }

        return null;
    }


}