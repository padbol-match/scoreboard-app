<?php
namespace App\Services\Match;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Services\Match\TenantService;
use App\Services\Scoreboard\TenantService as ScoreboardTenantService;
use Psr\Log\LoggerInterface;

class WordPressService
{
    private $httpClient;
    
    private $environment;

    public function __construct(
        HttpClientInterface $httpClient, 
        string $wpPostTitle, 
        string $wpPostCategory,
        string $wpAuthURL,
        string $wpRestURL,
        TenantService $tenantService,
        LoggerInterface $padbolLogger,
        ScoreboardTenantService $scoreboardTenantService)
    {
        $this->httpClient = $httpClient;
        $this->wpPostTitle = $wpPostTitle;
        $this->wpPostCategory = $wpPostCategory;
        $this->wpAuthURL = $wpAuthURL;
        $this->wpRestURL = $wpRestURL;
        $this->tenantService = $tenantService;
        $this->logger = $padbolLogger;
        $this->scoreboardTenantService = $scoreboardTenantService;
    }

    public function login($username, $password): array
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->wpAuthURL . '/token', [
                    'body' => [
                        "username" => $username,
                        "password" => $password
                    ]
                ]
            );

            $statusCode = $response->getStatusCode();
            // $statusCode = 200
            $contentType = $response->getHeaders()['content-type'][0];
            // $contentType = 'application/json'
            $content = $response->getContent();
            // $content = '{"id":521583, "name":"symfony-docs", ...}'
            $content = $response->toArray();
            // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

            $tenant = $this->tenantService->tenantForUserEmail($content["user_email"]);

            if(count($tenant) == 0){
                throw new \Exception("Not valid user with tenant");
            }

            $this->logger->info("Success Login", ["username" => $username, "tenant" => $tenant[0]->getDomain()]);

            $content["user_id"] = $tenant[0]->getUser()->getId();

            $scoreboardTenant = $this->scoreboardTenantService->register($tenant[0]->getId(), $content["user_id"]);

            if(is_null($scoreboardTenant)){
                throw new \Exception("Error creating Scoreboard Tenant");
            }

            $content["scoreboard_tenant_token"] = $scoreboardTenant->getToken();
            $content["scoreboard_tenant"] = $scoreboardTenant->getId();

            return $content;
        } catch (\Exception $e) {
            $this->logger->info("Error Login", ["error" => $e->getMessage()]);
            return ["error" => $e->getMessage()];
        }
    }

}