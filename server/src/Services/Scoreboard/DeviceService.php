<?php
namespace App\Services\Scoreboard;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\Scoreboard\DeviceRepository;
use App\Repository\Scoreboard\TenantRepository;
use App\Entity\Scoreboard\Device;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class DeviceService
{
    private $deviceRepository;
    private $tenantRepository;

    public function __construct(
        DeviceRepository $deviceRepository,
        TenantRepository $tenantRepository,
        EntityManagerInterface $entityManager)
    {
        $this->deviceRepository = $deviceRepository;
        $this->tenantRepository = $tenantRepository;
        $this->entityManager = $entityManager;
    }

    public function getDeviceIdByTenantAndField($tenant, $field){
        $device = $this->deviceRepository->findOneBy(["tenant" => $tenant, "field" => $field]);

        if(!is_null($device))
            return $device->getDeviceId();
        
        throw new \Exception("The device does not exist for Tenant: $tenant and field: $field");
    }

    public function register($deviceId): array
    {
        $result = [
            "token" => "-1",
            "registered" => false,
            "deviceId" => $deviceId
        ];

        try{
            $device = $this->deviceRepository->findOneByDeviceId($deviceId);

            if(
                $this->isRegistered($device) && 
                $this->isStillValid($device) &&
                $this->isLinked($device)
            ){
                $result["registered"] = true;
                $result["token"] = $device->getToken();
            } else if (!$this->isRegistered($device) || !$this->isStillValid($device)){
                $device = $this->generateNew($deviceId, $device);
                $result["token"] = $device->getToken();
            } else if(!is_null($device)){
                $result["token"] = $device->getToken();
            }
        }catch(\Exception $e){
        }

        return $result;
    }

    private function isRegistered(Device $device = null): bool
    {
        return !is_null($device);
    }

    private function isStillValid(Device $device = null): bool
    {
        if (is_null($device)){
            return false;
        }

        if($this->isLinked($device)){
            return true;
        }

        $now = new \DateTime();
        $deviceExpireDateTime = $device->getExpireDateTime();
        
        $time = $deviceExpireDateTime->diff($now)->i;

        return $time < 5;
    }

    private function isLinked(Device $device = null): bool
    {
        if (is_null($device)){
            return false;
        }

        return !is_null($device->getTenant());
    }

    private function generateNew(string $deviceId = "", Device $device = null): Device
    {
        if (!is_null($device)){
            $this->entityManager->remove($device);
        }

        if($deviceId == ""){
            throw new \Exception("Invalid deviceId");
        }

        //Expiration date
        $expireMinutes = 5;
        $expireDateTime = new \DateTime();
        $expireDateTime->modify('+5 minutes');

        //Token
        $token = strtoupper(substr(md5(microtime()),rand(0,26),4));

        $device = new Device();
        $device->setDeviceId($deviceId);
        $device->setExpireDateTime($expireDateTime);
        $device->setToken($token);

        $this->entityManager->persist($device);
        $this->entityManager->flush();

        return $device;
    }

    public function generateToken(){
        return ["token" => Uuid::uuid4()];
    }

    public function confirmTokens($fieldTokens, string $userId): array
    {
        $result = [];

        forEach($fieldTokens as $fieldId => $token){
            if(!is_null($token) && $token != ""){
                $tenant = $this->tenantRepository->findTenantByUserId($userId);
                        
                //Check if device already registered previously
                $oldDevice = $this->deviceRepository->findOneBy([
                    "tenant" => $tenant,
                    "field" => $fieldId
                ]);

                $newDevice = $this->deviceRepository->findOneBy([
                    "token" => $token, 
                    "tenant" => null
                ]);

                if(!is_null($oldDevice)){
                    if(!is_null($newDevice)){
                        $this->entityManager->remove($newDevice);
                        $this->entityManager->flush();
                    }
                    $device = $oldDevice;
                }else{
                    $device = $newDevice;
                }
                
                if(!is_null($device)){
                    $now = new \DateTime();
                    $deviceExpireDateTime = $device->getExpireDateTime();
            
                    $time = $deviceExpireDateTime->diff($now)->i;
                    
                    if($now < $deviceExpireDateTime){
                        $device->setTenant($tenant);
                        $device->setField($fieldId);

                        $this->entityManager->persist($device);
                        $this->entityManager->flush();

                        $result[$fieldId] = "OK";
                    }else{
                        $this->entityManager->remove($device);
                        $this->entityManager->flush();

                        $result[$fieldId] = "Token Expired";
                    }
                }else{
                    $result[$fieldId] = "Token Invalid";
                }
            }else{
                $result[$fieldId] = "No Token sent";
            }
        }

        return $result;
        
    }

    public function saveRemoteControlTeamButton($tenant, $field, $teamButton, $buttonCode){
        $device = $this->deviceRepository->findOneBy(["tenant" => $tenant, "field" => $field]);

        if(!is_null($device)){
            if($teamButton == 1){
                $device->setTeam1ButtonCode($buttonCode);
            }else if($teamButton == 2){
                $device->setTeam2ButtonCode($buttonCode);
            }else if($teamButton == 3){
                $device->setTeam3ButtonCode($buttonCode);
            }

            $this->entityManager->persist($device);
            $this->entityManager->flush();
        }
            
        
        return null;
    }

    public function getButtonCodes($tenant): array
    {
        $devices = $this->deviceRepository->findBy(["tenant" => $tenant]);
        $buttonCodes = [];

        forEach($devices as $device){
            $buttonCodes[$device->getField()] = [
                $device->getTeam1ButtonCode(),
                $device->getTeam2ButtonCode(),
                $device->getTeam3ButtonCode()
            ];
        }
        
        return $buttonCodes;
    }

    public function getTokens(string $userId): array
    {
        $result = [];
        
        $tenant = $this->tenantRepository->findTenantByUserId($userId);

        $devices = $this->deviceRepository->findBy([
            "tenant" => $tenant
        ]);

        foreach($devices as $device){
            $result[$device->getField()] = true;
        }

        return $result;
        
    }

    public function removeToken(string $userId, string $fieldId)
    {
        $tenant = $this->tenantRepository->findTenantByUserId($userId);

        $device = $this->deviceRepository->findOneBy([
            "tenant" => $tenant,
            "field" => $fieldId
        ]);

        $this->entityManager->remove($device);
        $this->entityManager->flush();        
    }

}