<?php
namespace App\Services\Scoreboard;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\Scoreboard\TenantRepository;
use App\Repository\Scoreboard\DeviceRepository;
use App\Repository\Match\TenantRepository as MatchTenantRepository;
use Ramsey\Uuid\Uuid;
use App\Entity\Scoreboard\Tenant;
use App\Entity\Scoreboard\Device;
use App\Entity\Scoreboard\Advertising;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AdvertisingService
{
    private $deviceRepository;

    public function __construct(
        DeviceRepository $deviceRepository,
        EntityManagerInterface $entityManager, 
        ParameterBagInterface $parameterBag)
    {
        $this->deviceRepository = $deviceRepository;
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;
        $this->publicPath = $this->parameterBag->get('kernel.project_dir') . "/public";
        $this->advertisingPublicFilePaths = "/advertising";
        $this->advertisingFilePaths = $this->publicPath . $this->advertisingPublicFilePaths;
    }

    public function getDevice(string $deviceId): Device 
    {
        $device = $this->deviceRepository->findOneByDeviceId($deviceId);

        return $device;
    }

    public function getDeviceByTenant(string $tenantId): Device 
    {
        $device = $this->deviceRepository->findOneByTenant($tenantId);

        return $device;
    }

    public function getFolderPath(string $tenant){
        //$device = $this->getDeviceByTenant($tenant);

        //$filePath = $this->advertisingFilePaths . "/" . $device->getDeviceId() . "/";
        $filePath = $this->advertisingFilePaths . "/" . $tenant . "/";

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        return $filePath;
    }

    public function getPublicImagePath(string $tenant, string $fileName): string 
    {
        $file = $this->advertisingPublicFilePaths . "/" . $tenant . "/" . $fileName . ".png";
        
        if (file_exists($this->publicPath . $file)) {
            return $file;
        }

        $file = $this->advertisingPublicFilePaths . "/" . $tenant . "/" . $fileName . ".jpg";

        if (file_exists($this->publicPath . $file)) {
            return $file;
        }

        return $this->getDefaultPublicImagePath($fileName);
    }

    private function getDefaultPublicImagePath(string $fileName): string 
    {   
        $filePath = "/assets/images/scoreboard/"; 
        $file = $filePath . $fileName . ".png";

        if (file_exists($this->publicPath . $file)) {
            return $file;
        }

        $file = $filePath . $fileName . ".jpg";

        if (file_exists($this->publicPath . $file)) {
            return $file;
        }

        return "";
    }

    public function create(string $tenant): array
    {
        return [
            Advertising::IMAGE_FOOTER => $this->getPublicImagePath($tenant, Advertising::IMAGE_FOOTER),
            Advertising::IMAGE_SLIDE_1 => $this->getPublicImagePath($tenant, Advertising::IMAGE_SLIDE_1),
            Advertising::IMAGE_SLIDE_2 => $this->getPublicImagePath($tenant, Advertising::IMAGE_SLIDE_2),
            Advertising::IMAGE_SLIDE_3 => $this->getPublicImagePath($tenant, Advertising::IMAGE_SLIDE_3),
            Advertising::IMAGE_SLIDE_4 => $this->getPublicImagePath($tenant, Advertising::IMAGE_SLIDE_4)
        ];
    }

    public function removeFiles(string $tenant, string $fileName){
        //$device = $this->getDeviceByTenant($tenant);
        //$deviceId = $device->getDeviceId();
        
        try{
            $file = $this->advertisingPublicFilePaths . "/" . $tenant . "/" . $fileName . ".png";
            if (file_exists($this->publicPath . $file)) {
                unlink($this->publicPath . $file);
            }

            $file = $this->advertisingPublicFilePaths . "/" . $tenant . "/" . $fileName . ".jpg";
            if (file_exists($this->publicPath . $file)) {
                unlink($this->publicPath . $file);
            }
        } catch(\Exception $e){
            throw new \Exception("Problems removing files");
        }
    }

}