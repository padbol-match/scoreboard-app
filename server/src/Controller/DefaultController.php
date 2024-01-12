<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Scoreboard\DeviceService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class DefaultController
 * @package App\Controller
 *
 */
class DefaultController
{
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;

    }

    /**
     * @Route("/register-devices")
     * @Route("/advertising")
     * @Route("/scoreboards")
     * @Route("/scoreboards-config")
     * @Route("/start-match/{tenant}/{field}")
     * @Route("/field-qr/{tenant}/{field}")
     * @Route("/")
     */
    public function index(Request $request): Response
    {
        $angularPath = $this->parameterBag->get('kernel.project_dir') . "/public/index.html";
        return new Response(file_get_contents($angularPath));
    }
}