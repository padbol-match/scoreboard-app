<?php
namespace App\Services\Scoreboard;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\Scoreboard\TenantRepository;
use App\Repository\Scoreboard\DeviceRepository;
use App\Repository\Match\TenantRepository as MatchTenantRepository;
use Ramsey\Uuid\Uuid;
use App\Entity\Scoreboard\Tenant;
use Doctrine\ORM\EntityManagerInterface;

class TenantService
{
    private $tenantRepositry;

    public function __construct(
        TenantRepository $tenantRepository,
        DeviceRepository $deviceRepository,
        EntityManagerInterface $entityManager,
        MatchTenantRepository $matchTenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
        $this->deviceRepository = $deviceRepository;
        $this->entityManager = $entityManager;
        $this->matchTenantRepository = $matchTenantRepository;
    }

    public function register(string $tenantId, string $userId): Tenant
    {
        if(!is_null($tenantId)){
            $tenant = $this->tenantRepository->findOneByTenant($tenantId);

            if(is_null($tenant)){
                $tenant = new Tenant();
                $tenant->setTenant($tenantId);
                $tenant->setToken(Uuid::uuid4());

                $this->entityManager->persist($tenant);
                $this->entityManager->flush();
            }
            return $tenant;
        }

        return null;
        
    }

    public function getFields(string $userId){
        $matchTenant = $this->matchTenantRepository->findOneByUser($userId);
        $scoreboardTenant = $this->tenantRepository->findOneByTenant($matchTenant->getId());
        $devices = $this->deviceRepository->findByTenant($scoreboardTenant->getId());

        $fields = [];

        forEach($devices as $device ){
            $fields[] = [
                "id" => $device->getField(),
                "press_team_1" => 0,
                "press_team_2" => 0,
                "press_back" => 0  
            ];
        }

        return $fields;
    }

    public function findTenantByUserId(string $userId){
        $tenant = $this->tenantRepository->findTenantByUserId($userId);

        return $tenant->getId();
    }

    public function findTenantById(string $tenantId){
        $tenant = $this->tenantRepository->findOneById($tenantId);

        return $tenant->getId();
    }

}