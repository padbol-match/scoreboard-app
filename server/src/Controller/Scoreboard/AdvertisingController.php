<?php
namespace App\Controller\Scoreboard;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Scoreboard\DeviceService;
use App\Services\Scoreboard\AdvertisingService;
use App\Entity\Scoreboard\Advertising;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AdvertisingController
 * @package App\Controller
 *
 * @Route(path="/api")
 */
class AdvertisingController
{

    public function __construct(DeviceService $deviceService, AdvertisingService $advertisingService)
    {
        $this->deviceService = $deviceService;
        $this->advertisingService = $advertisingService;
    }

    /**
     * @Route("/advertising/get_images_from_tenant/{tenant}", name="get_images_from_tenant", methods={"GET"})
     */
    public function getImagesFromTenant(Request $request, string $tenant): JsonResponse
    {
        //$device = $this->advertisingService->getDeviceByTenant($tenant);
        //return $this->getImages($request, $device->getDeviceId());

        return $this->getImages($request, $tenant);
    }

    /**
     * @Route("/advertising/get_images/{tenant}", name="get_images", methods={"GET"})
     */
    public function getImages(Request $request, string $tenant): JsonResponse
    {
        $result = [
            Advertising::IMAGE_FOOTER => $this->advertisingService->getPublicImagePath($tenant, Advertising::IMAGE_FOOTER),
            Advertising::IMAGE_SLIDE_1 => $this->advertisingService->getPublicImagePath($tenant, Advertising::IMAGE_SLIDE_1),
            Advertising::IMAGE_SLIDE_2 => $this->advertisingService->getPublicImagePath($tenant, Advertising::IMAGE_SLIDE_2),
            Advertising::IMAGE_SLIDE_3 => $this->advertisingService->getPublicImagePath($tenant, Advertising::IMAGE_SLIDE_3),
            Advertising::IMAGE_SLIDE_4 => $this->advertisingService->getPublicImagePath($tenant, Advertising::IMAGE_SLIDE_4)
        ];

        return new JsonResponse(
            $result, 
            Response::HTTP_OK 
        );
    }

    /**
     * @Route("/advertising/get_footer/{tenant}", name="get_footer", methods={"POST"})
     */
    public function getFooterImage(Request $request, string $tenant): JsonResponse
    {
        $result = [
            Advertising::IMAGE_FOOTER => $this->advertisingService->getPublicImagePath(
                $tenant, 
                Advertising::IMAGE_FOOTER)
        ];

        return new JsonResponse(
            $result, 
            Response::HTTP_OK 
        );
    }

    /**
     * @Route("/advertising/get_slides/{tenant}", name="get_slides", methods={"POST"})
     */
    public function getSlideImages(Request $request, string $tenant): JsonResponse
    {
        $result = [
            Advertising::IMAGE_SLIDE_1 => $this->advertisingService->getPublicImagePath(
                $tenant, Advertising::IMAGE_SLIDE_1),
            Advertising::IMAGE_SLIDE_2 => $this->advertisingService->getPublicImagePath(
                $tenant, Advertising::IMAGE_SLIDE_2),
            Advertising::IMAGE_SLIDE_3 => $this->advertisingService->getPublicImagePath(
                $tenant, Advertising::IMAGE_SLIDE_3),
            Advertising::IMAGE_SLIDE_4 => $this->advertisingService->getPublicImagePath(
                $tenant, Advertising::IMAGE_SLIDE_4)
        ];

        return new JsonResponse(
            $result, 
            Response::HTTP_OK 
        );
    }

    /**
     * @Route("/advertising/upload_footer_image", name="upload_footer_image", methods={"POST"})
     */
    public function uploadFooterImage(Request $request): JsonResponse
    {
        $tenant = $request->get('tenant');

        $files = $request->files->all();

        $filePath = $this->advertisingService->getFolderPath($tenant);
        $this->advertisingService->removeFiles($tenant, Advertising::IMAGE_FOOTER);

        foreach ($files as $file) {
            $fileName = Advertising::IMAGE_FOOTER . '.' . $file->guessExtension();
            $original_name = $file->getClientOriginalName ();
            $file->move($filePath,  $fileName);
            $file_entity = new UploadedFile($filePath . $fileName, $original_name);
        }

        $data = [
            'status' => 'success',
            'code' => 200,
            'message' => 'OK',
            'filetest' => $files
        ];
        
        return new JsonResponse(
            $data, 
            Response::HTTP_OK 
        );
    }

    /**
     * @Route("/advertising/upload_slide_images", name="upload_slide_images", methods={"POST"})
     */
    public function uploadSlideImages(Request $request): JsonResponse
    {
        $tenant = $request->get('tenant');

        $files = $request->files->all();

        $filePath = $this->advertisingService->getFolderPath($tenant);

        foreach ($files as $key => $file) {
            $index = strrchr( $key, '_');
            
            $this->advertisingService->removeFiles($tenant, "slide" . $index);
            
            $fileName = "slide" . $index . '.' . $file->guessExtension();
            $original_name = $file->getClientOriginalName ();
            $file->move($filePath,  $fileName);
            $file_entity = new UploadedFile($filePath . $fileName, $original_name);
        }

        $data = [
            'status' => 'success',
            'code' => 200,
            'message' => 'OK',
            'filetest' => $files
        ];
        
        return new JsonResponse(
            $data, 
            Response::HTTP_OK 
        );
    }

}

?>
