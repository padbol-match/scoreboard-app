<?php
namespace App\Controller\Scoreboard;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Scoreboard\MatchService;
use App\Services\Scoreboard\DeviceService;
use App\Services\Scoreboard\TenantService;
use App\Services\Scoreboard\AdvertisingService;
use App\Entity\Scoreboard\Selector;
use App\Entity\Scoreboard\Match;
/**
 * Class MatchController
 * @package App\Controller
 *
 * @Route(path="/api/match")
 */
class MatchController
{

    public function __construct(
        MatchService $matchService, 
        DeviceService $deviceService,
        TenantService $tenantService,
        AdvertisingService $advertisingService)
    {
        $this->matchService = $matchService;
        $this->deviceService = $deviceService;
        $this->tenantService = $tenantService;
        $this->advertisingService = $advertisingService;
    }
    
    /**
     * @Route("/get", name="get", methods={"POST"})
     */
    public function get(Request $request): JsonResponse
    {
        try{
            $deviceId = $this->deviceService->getDeviceIdByTenantAndField(
                $request->get('tenant'), $request->get('field')
            );

            $match = $this->matchService->findMatch($deviceId);

            return new JsonResponse(
                $this->getSelector($request->get('tenant'), $deviceId, $match, Selector::MESSAGE_GET), 
                Response::HTTP_OK
            );
        }catch(\Exception $e){
            return new JsonResponse(
                $e->getMessage(), 
                Response::HTTP_INTERNAL_SERVER_ERROR 
            );
        }
    }

    /**
     * @Route("/save", name="save", methods={"POST"})
     */
    public function save(Request $request): JsonResponse
    {
        try {
            $deviceId = $this->deviceService->getDeviceIdByTenantAndField(
                $request->get('tenant'), $request->get('field')
            );

            $match = $this->matchService->save(
                $deviceId,
                $request->get('team1'),
                $request->get('team2'),
                $request->get('player1Team1'),
                $request->get('player2Team1'),
                $request->get('player3Team1'),
                $request->get('player1Team2'),
                $request->get('player2Team2'),
                $request->get('player3Team2'),
                $request->get('field')
            );

            return new JsonResponse(
                $this->getSelector($request->get('tenant'), $deviceId, $match, Selector::MESSAGE_SAVE), 
                Response::HTTP_OK
            );
        }catch(\Exception $e){
            return new JsonResponse(
                $e->getMessage(), 
                Response::HTTP_INTERNAL_SERVER_ERROR 
            );
        }
    }

    /**
     * @Route("/start", name="start", methods={"POST"})
     */
    public function start(Request $request): JsonResponse
    {
        try{ 
            $deviceId = $this->deviceService->getDeviceIdByTenantAndField(
                $request->get('tenant'), $request->get('field')
            );

            $match = $this->matchService->config(
                $request->get('tenant'),
                $deviceId,
                $request->get('team1'),
                $request->get('team2'),
                $request->get('player1Team1'),
                $request->get('player2Team1'),
                $request->get('player3Team1'),
                $request->get('player1Team2'),
                $request->get('player2Team2'),
                $request->get('player3Team2'),
                $request->get('field')
            );

            return new JsonResponse(
                $this->getSelector($request->get('tenant'), $deviceId, $match, Selector::MESSAGE_START), 
                Response::HTTP_OK
            );
        }catch(\Exception $e){
            var_dump($e->getMessage());
            return new JsonResponse(
                $e->getMessage(), 
                Response::HTTP_INTERNAL_SERVER_ERROR 
            );
        }
    }

    /**
     * @Route("/add_point_team", name="add_point_team", methods={"POST"})
     */
    public function addPointTeam(Request $request): JsonResponse
    {
        try{
            $deviceId = $this->deviceService->getDeviceIdByTenantAndField(
                $request->get('tenant'), $request->get('field')
            );

            $match = $this->matchService->addPointTeam(
                $deviceId,
                $request->get('team')
            );

            return new JsonResponse(
                $this->getSelector($request->get('tenant'), $deviceId, $match, Selector::MESSAGE_ADD_POINT), 
                Response::HTTP_OK
            );
        }catch(\Exception $e){
            return new JsonResponse(
                $e->getMessage(), 
                Response::HTTP_INTERNAL_SERVER_ERROR 
            );
        }
    }

    /**
     * @Route("/sub_point_team", name="sub_point_team", methods={"POST"})
     */
    public function subPointTeam(Request $request): JsonResponse
    {
        try{
            $deviceId = $this->deviceService->getDeviceIdByTenantAndField(
                $request->get('tenant'), $request->get('field')
            );

            $match = $this->matchService->subPointTeam(
                $deviceId
            );

            return new JsonResponse(
                $this->getSelector($request->get('tenant'), $deviceId, $match, Selector::MESSAGE_ADD_POINT), 
                Response::HTTP_OK
            );
        }catch(\Exception $e){
            return new JsonResponse(
                $e->getMessage(), 
                Response::HTTP_INTERNAL_SERVER_ERROR 
            );
        }
    }

    /**
     * @Route("/stop", name="stop", methods={"POST"})
     */
    public function stop(Request $request): JsonResponse
    {
        try{
            $deviceId = $this->deviceService->getDeviceIdByTenantAndField(
                $request->get('tenant'), $request->get('field')
            );

            $match = $this->matchService->stop(
                $deviceId
            );

            return new JsonResponse(
                $this->getSelector($request->get('tenant'), $deviceId, $match, Selector::MESSAGE_STOP), 
                Response::HTTP_OK
            );
        }catch(\Exception $e){
            return new JsonResponse(
                $e->getMessage(), 
                Response::HTTP_INTERNAL_SERVER_ERROR 
            );
        }
    }

    /**
     * @Route("/restart", name="restart", methods={"POST"})
     */
    public function restart(Request $request): JsonResponse
    {
        try{
            $deviceId = $this->deviceService->getDeviceIdByTenantAndField(
                $request->get('tenant'), $request->get('field')
            );

            $match = $this->matchService->restart(
                $deviceId
            );

            return new JsonResponse(
                $this->getSelector($request->get('tenant'), $deviceId, $match, Selector::MESSAGE_RESTART), 
                Response::HTTP_OK
            );
        }catch(\Exception $e){
            return new JsonResponse(
                $e->getMessage(), 
                Response::HTTP_INTERNAL_SERVER_ERROR 
            );
        }
    }

    private function getSelector(string $tenant, string $deviceId, Match $match = null, $message)
    {
        if($deviceId == -1 || is_null($match)){
            return null;
        }
        $selector = new Selector();

        $advertising = $this->advertisingService->create($tenant);

        $selector->init($deviceId, $message, $match, $advertising);

        return $selector;
    }

}

?>
