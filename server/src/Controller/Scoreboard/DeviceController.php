<?php
namespace App\Controller\Scoreboard;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Scoreboard\DeviceService;

/**
 * Class SecurityController
 * @package App\Controller
 *
 * @Route(path="/api")
 */
class DeviceController
{

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    /**
     * @Route("/device/register", name="device_register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);

        $deviceId = $parameters['device_id'];

        try{
            return new JsonResponse(
                $this->deviceService->register($deviceId), 
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
     * @Route("/device/confirm-tokens/{userId}", name="device_confirm_tokens", methods={"POST"})
     */
    public function confirmTokens(Request $request, string $userId): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $fieldTokens = [];

        for ($i = 1; $i <= 4; $i++) {
            $fieldTokens[$i] = $parameters['field_' . $i];
        }

        try{
            return new JsonResponse(
                $this->deviceService->confirmTokens($fieldTokens, $userId), 
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
     * @Route("/device/generate-token", name="generate_token", methods={"GET"})
     */
    public function generateToken(Request $request): JsonResponse
    {
        return new JsonResponse(
            $this->deviceService->generateToken(), 
            Response::HTTP_OK
        );
    }

    /**
     * @Route(
     *  "/device/register-remote-control-team-button", 
     *  name="register_remote_control_team_button", 
     *  methods={"POST"}
     * )
     */
    public function registerRemoteControlTeamButton(Request $request): JsonResponse
    {
        $this->deviceService->saveRemoteControlTeamButton(
            $request->get('tenant'), 
            $request->get('field'), 
            $request->get('teamButton'),
            $request->get('code')
        );

        return new JsonResponse(
            [], 
            Response::HTTP_OK
        );
    }

    /**
     * @Route(
     *  "/device/get-button-codes", 
     *  name="get_button_codes", 
     *  methods={"POST"}
     * )
     */
    public function getButtonCodes(Request $request): JsonResponse
    {
        $buttonCodes = $this->deviceService->getButtonCodes(
            $request->get('tenant')
        );

        return new JsonResponse(
            $buttonCodes, 
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/device/get-tokens/{userId}", name="device_get_tokens", methods={"GET"})
     */
    public function getTokens(Request $request, string $userId): JsonResponse
    {
        try{
            return new JsonResponse(
                $this->deviceService->getTokens($userId), 
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
     * @Route("/device/remove-token/{userId}", name="device_remove_token", methods={"POST"})
     */
    public function removeToken(Request $request, string $userId): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);

        try{
            return new JsonResponse(
                $this->deviceService->removeToken($userId, $parameters["field"]), 
                Response::HTTP_OK
            );
        } catch(\Exception $e){
            return new JsonResponse(
                ["error" => $e->getMessage()], 
                Response::HTTP_NOT_ACCEPTABLE 
            );
        }
    }

}

?>
