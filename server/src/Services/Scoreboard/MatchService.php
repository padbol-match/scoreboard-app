<?php
namespace App\Services\Scoreboard;

use App\Entity\Scoreboard\Match;
use App\Entity\Scoreboard\Selector;
use App\Services\Scoreboard\AdvertisingService;
use App\Services\WebSocket\WebSocketClient;
use DateTime;

class MatchService
{

    public function __construct(
        string $basePath, 
        string $websocketDomain,
        string $websocketPort,
        AdvertisingService $advertisingService,
        WebSocketClient $webSocketClient)
    {
        $this->file = $basePath . '/public/matches/';
        $this->websocketDomain = $websocketDomain;
        $this->websocketPort = $websocketPort;
        $this->advertisingService = $advertisingService;
        $this->webSocketClient = $webSocketClient;
    }

    public function config(
        $tenant,
        $deviceId, 
        $team1 = "", 
        $team2 = "", 
        $player1Team1 = "", 
        $player2Team1 = "", 
        $player3Team1 = "", 
        $player1Team2 = "", 
        $player2Team2 = "", 
        $player3Team2 = "",
        $field = -1)
    {
        $match = new Match();
        $match->init($tenant, $deviceId, $team1, $team2, $player1Team1, $player2Team1, $player3Team1, $player1Team2, $player2Team2, $player3Team2, $field);
        
        $this->saveMatch($match);

        $this->sendMessageToWebsocket($match, Selector::MESSAGE_START);

        return $match; 
    }

    public function save(
        $deviceId, 
        $team1 = null, 
        $team2 = null, 
        $player1Team1 = null, 
        $player2Team1 = null, 
        $player3Team1 = null, 
        $player1Team2 = null, 
        $player2Team2 = null, 
        $player3Team2 = null,
        $field = null)
    {
        $match = $this->findMatch($deviceId);
        
        $match->setter($team1, $team2, $player1Team1, $player2Team1, $player3Team1, $player1Team2, $player2Team2, $player3Team2);
        
        $this->saveMatch($match);

        $this->sendMessageToWebsocket($match, Selector::MESSAGE_SAVE);

        return $match; 
    }

    public function addPointTeam1($deviceId){
        return $this->addPointTeam($deviceId, 1);
    }

    public function addPointTeam2($deviceId){
        return $this->addPointTeam($deviceId, 2);
    }

    public function addPointTeam($deviceId, $team){
        $match = $this->findMatch($deviceId);

        if(!is_null($match)){
            $match->addPointTeam($team);
            $this->saveMatch($match);
        }else{
            throw new \Exception("The math does not exist");
        }

        $this->sendMessageToWebsocket($match, Selector::MESSAGE_ADD_POINT);

        $match->history = [];

        return $match;
    }

    public function subPointTeam($deviceId){
        $match = $this->findMatch($deviceId);

        if(!is_null($match)){
            $match->subPointTeam();
            $this->saveMatch($match);
        }else{
            $match = [];
        }

        $this->sendMessageToWebsocket($match, Selector::MESSAGE_SUB_POINT);

        $match->history = [];

        return $match;
    }

    public function stop($deviceId){
        $match = $this->findMatch($deviceId);

        $this->removeMatch($deviceId);

        $this->sendMessageToWebsocket($match, Selector::MESSAGE_STOP);

        return $match;
    }

    public function restart($deviceId){
        $match = $this->findMatch($deviceId);

        $match->restart();
        $this->saveMatch($match);

        $this->sendMessageToWebsocket($match, Selector::MESSAGE_RESTART);

        return $match;
    }

    public function findMatch($deviceId){
        try{
            $fileName = $this->file . $deviceId .".json";
            $file = fopen($fileName,"r");

            $fileContent = fread($file, filesize($fileName));
            fclose($file);
            $data = json_decode($fileContent, true);

            $match = new Match();
            foreach ($data as $key => $value) {
                if($key == "startTime"){
                    $match->{$key} = new DateTime($value["date"]);
                }else{
                    $match->{$key} = $value;
                }
            }

            return $match;
        } catch(\Exception $e){
            return null;
        }
        
        return null;
    }

    private function saveMatch($match){
        $file = fopen($this->file . $match->getDeviceId() .".json","w");
        fwrite($file, json_encode($match));
        fclose($file);
    }

    private function removeMatch($deviceId){
        unlink($this->file . $deviceId .".json");
    }
    
    /*
    private function sendMessageToWebsocket($match, $message){
        try{
            $selector = new Selector();
            $advertising = $this->advertisingService->create($match->getTenant());
            $selector->init($match->getDeviceId(), $message, $match, $advertising);
            $payload = json_encode($selector);

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_URL, $this->websocketDomain);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_FAILONERROR, true); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);//SSL Problem  

            //execute post
            $result = curl_exec($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $httpError = curl_error($ch);

            curl_close($ch);
            
            if ($httpCode != 200) {
                throw new \Exception("Curl ERROR: " . $httpError);
            }
            

            return $result;
        } catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }*/

    public function sendMessageToWebsocket($match, $message){
        try{
            $selector = new Selector();
            $advertising = $this->advertisingService->create($match->getTenant());
            $selector->init($match->getDeviceId(), $message, $match, $advertising);
            $message = json_encode($selector);

            if( $sp = $this->webSocketClient->open($this->websocketDomain, $this->websocketPort,'', $errstr) ) {
                $this->webSocketClient->write($sp, $message);
            }else{
                throw new \Exception("Error sending message to websocket: " . $errstr);
            }
        } catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

}