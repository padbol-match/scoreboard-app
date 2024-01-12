<?php
namespace App\Services\Match;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\Match\TenantRepository;

class TenantService
{
    private $tenantRepositry;

    public function __construct(
        TenantRepository $tenantRepositry)
    {
        $this->tenantRepositry = $tenantRepositry;;
    }

    public function tenantForUserEmail($userEmail): array
    {
        return $this->tenantRepositry->findTenantByUserEmail($userEmail);
    }

}