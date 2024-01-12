<?php
namespace App\Controller\Scoreboard;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Scoreboard\TenantService;
use App\Services\Scoreboard\DeviceService;

/**
 * Class SecurityController
 * @package App\Controller
 *
 * @Route(path="/api")
 */
class TenantController
{

    public function __construct(TenantService $tenantService, DeviceService $deviceService)
    {
        $this->tenantService = $tenantService;
        $this->deviceService = $deviceService;
    }

    /**
     * @Route("/tenant/register/{userId}", name="tenant_register", methods={"POST"})
     */
    public function register(Request $request, string $userId): JsonResponse
    {
        try{
            return new JsonResponse(
                $this->tenantService->register($userId), 
                Response::HTTP_OK
            );
        } catch(\Exception $e){
            return new JsonResponse(
                ["error" => $e->getMessage()], 
                Response::HTTP_NOT_ACCEPTABLE 
            );
        }
    }
    
    /**
     * @Route("/tenant/get-fields/{userId}", name="get-fields", methods={"GET"})
     */
    public function getFields(string $userId): JsonResponse
    {
        return new JsonResponse(
            $this->tenantService->getFields($userId), 
            Response::HTTP_OK
        );
    }

}

?>
