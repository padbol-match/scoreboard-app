<?php

namespace App\Entity\Scoreboard;

use DateTime;

class Match
{
    const SETS = 3;
    const GAMES = 5;

    public string $deviceId;

    public string $team1;

    public string $team2;

    public string $player1Team1;

    public string $player2Team1;

    public string $player3Team1;

    public string $player1Team2;

    public string $player2Team2;

    public string $player3Team2;

    public array $gameTeam;

    public int $set;

    public array $setTeam;

    public array $scoreTeam;

    public int $activeTurn;

    public int $winner;

    public array $history;

    public int $field;

    public DateTime $startTime;

    public array $setAndGameHistory;

    public int $tenant;

    public int $advantage;

    public function __construct()
    {
        $this->scoreTeam = [0, 0];
        $this->gameTeam = [0, 0];
        $this->set = 1;
        $this->setTeam = [0, 0];
        $this->activeTurn = 0;
        $this->history = [];
        $this->startTime = new \DateTime();
        $this->setAndGameHistory = [[0,0],[0,0],[0,0]];
        $this->advantage = 0;
    }

    public function init(
        $tenant,
        $deviceId, 
        $team1, 
        $team2, 
        $player1Team1, 
        $player2Team1, 
        $player3Team1, 
        $player1Team2, 
        $player2Team2,
        $player3Team2,
        $field)
    {
        $this->tenant = $tenant;
        $this->deviceId = $deviceId;

        $this->setter($team1, $team2, $player1Team1, $player2Team1, $player3Team1, $player1Team2, $player2Team2, $player3Team2);
        
        $this->field = !is_null($field) ? $field : "1";
        
        $this->scoreTeam = [0, 0];
        $this->gameTeam = [0, 0];
        $this->set = 1;
        $this->setTeam = [0, 0];
        $this->activeTurn = 0;
        $this->history = [];
        $this->startTime = new \DateTime();
        $this->setAndGameHistory = [[0,0],[0,0],[0,0]];
        $this->advantage = 0;
    }

    public function setter(
        $team1, 
        $team2, 
        $player1Team1, 
        $player2Team1, 
        $player3Team1, 
        $player1Team2, 
        $player2Team2,
        $player3Team2)
    {
        $this->team1 = !is_null($team1) ? $team1 : "Team 1";
        $this->team2 = !is_null($team2) ? $team2 : "Team 2";
        $this->player1Team1 = !is_null($player1Team1) ? $player1Team1 : "T1 - Player 1";
        $this->player2Team1 = !is_null($player2Team1) ? $player2Team1 : "T1 - Player 2";
        $this->player3Team1 = !is_null($player3Team1) ? $player3Team1 : "T1 - Player 3";
        $this->player1Team2 = !is_null($player1Team2) ? $player1Team2 : "T2 - Player 1";
        $this->player2Team2 = !is_null($player2Team2) ? $player2Team2 : "T2 - Player 2";
        $this->player3Team2 = !is_null($player3Team2) ? $player3Team2 : "T2 - Player 3";
    }

    public function restart()
    {
        $initialMatch = $this->getHistory()[0];

        $this->scoreTeam = [0, 0];
        $this->gameTeam = [0, 0];
        $this->set = 1;
        $this->setTeam = [0, 0];
        $this->activeTurn = 0;
        $this->history = [];
        $this->startTime = new \DateTime();
        $this->setAndGameHistory = [[0,0],[0,0],[0,0]];
        $this->advantage = 0;
    }

    public function addPointTeam($team){
        //Advantage
        if(($this->getScoreTeam(1) == 40) && ($this->getScoreTeam(2) == 40) && !$this->isInAdvantage()){
            $this->setAdvantage($team);
        }else if(($this->getScoreTeam(1) == 40) && ($this->getScoreTeam(2) == 40) && $this->isInAdvantage()){
            if($this->getAdvantage($team) == $team){
                $this->addGameTeam($team);
                $this->restartScores();
            }else{
                $this->setAdvantage(0);
            }
        }else if($this->getScoreTeam($team) == 40){
            $this->addGameTeam($team);
            $this->restartScores();
        }else{
            switch($this->getScoreTeam($team)){
                case 0: $this->setScoreTeam($team, 15); break;
                case 15: $this->setScoreTeam($team, 30); break;
                case 30: $this->setScoreTeam($team, 40); break;
            }
        }

        $this->addHistory();
    }

    public function subPointTeam(){
        if($this->hasHistory()){
            $previousMatch = $this->goBackHistory();

            $this->scoreTeam = $previousMatch['scoreTeam'];
            $this->gameTeam = $previousMatch['gameTeam'];
            $this->set = $previousMatch['set'];
            $this->setTeam = $previousMatch['setTeam'];
            $this->activeTurn = $previousMatch['activeTurn'];
        }
    }

    public function addGameTeam($team){
        if($this->getGameTeam($team) == self::GAMES - 1){
            $this->setGameTeam($team, $this->getGameTeam($team) + 1);
            $this->addSetTeam($team);
            $this->restartGames();
        }else{
            $this->setGameTeam($team, $this->getGameTeam($team) + 1);
        }
    }

    public function addSetTeam($team){
        $this->addSetAndGameHistory();
        $this->setSetTeam($team, $this->getSetTeam($team) + 1);

        if($this->set == self::SETS){
            $this->finish($team);
        }

        $this->set++;
    }

    public function finish($team){
        $this->winner = $team;
    }

    public function restartScores(){
        $this->scoreTeam[0] = 0;
        $this->scoreTeam[1] = 0;
        $this->setAdvantage(0);
    }

    public function restartGames(){
        $this->gameTeam[0] = 0;
        $this->gameTeam[1] = 0;
    }

    public function addHistory(){
        $matchHistory = clone $this;
        $matchHistory->setHistory([]);

        array_push($this->history, $matchHistory);
    }

    public function goBackHistory(){
        if($this->hasHistory()){
            array_pop($this->history);
            return $this->history[count($this->history)-1];
        }
    }

    /**
     * Get the value of deviceId
     */ 
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * Set the value of deviceId
     *
     * @return  self
     */ 
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    /**
     * Get the value of team1
     */ 
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * Set the value of team1
     *
     * @return  self
     */ 
    public function setTeam1($team1)
    {
        $this->team1 = $team1;

        return $this;
    }

    /**
     * Get the value of team2
     */ 
    public function getTeam2()
    {
        return $this->team2;
    }

    /**
     * Set the value of team2
     *
     * @return  self
     */ 
    public function setTeam2($team2)
    {
        $this->team2 = $team2;

        return $this;
    }

    /**
     * Get the value of player1Team1
     */ 
    public function getPlayer1Team1()
    {
        return $this->player1Team1;
    }

    /**
     * Set the value of player1Team1
     *
     * @return  self
     */ 
    public function setPlayer1Team1($player1Team1)
    {
        $this->player1Team1 = $player1Team1;

        return $this;
    }

    /**
     * Get the value of player2Team1
     */ 
    public function getPlayer2Team1()
    {
        return $this->player2Team1;
    }

    /**
     * Set the value of player2Team1
     *
     * @return  self
     */ 
    public function setPlayer2Team1($player2Team1)
    {
        $this->player2Team1 = $player2Team1;

        return $this;
    }

    /**
     * Get the value of player1Team2
     */ 
    public function getPlayer1Team2()
    {
        return $this->player1Team2;
    }

    /**
     * Set the value of player1Team2
     *
     * @return  self
     */ 
    public function setPlayer1Team2($player1Team2)
    {
        $this->player1Team2 = $player1Team2;

        return $this;
    }

    /**
     * Get the value of player2Team2
     */ 
    public function getPlayer2Team2()
    {
        return $this->player2Team2;
    }

    /**
     * Set the value of player2Team2
     *
     * @return  self
     */ 
    public function setPlayer2Team2($player2Team2)
    {
        $this->player2Team2 = $player2Team2;

        return $this;
    }

    /**
     * Get the value of scoreTeam
     */ 
    public function getScoreTeam($team)
    {
        return $this->scoreTeam[$team-1];
    }

    /**
     * Set the value of scoreTeam
     *
     * @return  self
     */ 
    public function setScoreTeam($team, $value)
    {
        $this->scoreTeam[$team-1] = $value;

        return $this;
    }

    /**
     * Get the value of gameTeam
     */ 
    public function getGameTeam($team)
    {
        return $this->gameTeam[$team-1];
    }

    /**
     * Set the value of gameTeam
     *
     * @return  self
     */ 
    public function setGameTeam($team, $value)
    {
        $this->gameTeam[$team-1] = $value;
        
        $this->setAndGameHistory[$this->set-1] = $this->gameTeam;

        return $this;
    }

    /**
     * Get the value of setTeam
     */ 
    public function getSetTeam($team)
    {
        return $this->setTeam[$team - 1];
    }

    /**
     * Set the value of setTeam
     *
     * @return  self
     */ 
    public function setSetTeam($team, $value)
    {
        $this->setTeam[$team - 1] = $value;

        return $this;
    }

    /**
     * Get the value of history
     */ 
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Set the value of history
     *
     * @return  self
     */ 
    public function setHistory($history)
    {
        $this->history = $history;

        return $this;
    }

    /**
     * Has history
     */ 
    public function hasHistory(): bool
    {
        return count($this->history) > 1;
    }

    /**
     * Get the value of player3Team1
     */ 
    public function getPlayer3Team1()
    {
        return $this->player3Team1;
    }

    /**
     * Set the value of player3Team1
     *
     * @return  self
     */ 
    public function setPlayer3Team1($player3Team1)
    {
        $this->player3Team1 = $player3Team1;

        return $this;
    }

    /**
     * Get the value of player3Team2
     */ 
    public function getPlayer3Team2()
    {
        return $this->player3Team2;
    }

    /**
     * Set the value of player3Team2
     *
     * @return  self
     */ 
    public function setPlayer3Team2($player3Team2)
    {
        $this->player3Team2 = $player3Team2;

        return $this;
    }

    /**
     * Get the value of field
     */ 
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set the value of field
     *
     * @return  self
     */ 
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get the value of startTime
     */ 
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set the value of startTime
     *
     * @return  self
     */ 
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get the value of setAndGameHistory
     */ 
    public function getSetAndGameHistory()
    {
        return $this->setAndGameHistory;
    }

    /**
     * Set the value of setAndGameHistory
     *
     * @return  self
     */ 
    public function setSetAndGameHistory($setAndGameHistory)
    {
        $this->setAndGameHistory = $setAndGameHistory;

        return $this;
    }

     /**
     * Set the value of setAndGameHistory
     *
     * @return  self
     */ 
    public function addSetAndGameHistory()
    {
        $this->setAndGameHistory[$this->set-1] = $this->gameTeam;

        return $this;
    }

    /**
     * Get the value of tenant
     */ 
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Set the value of tenant
     *
     * @return  self
     */ 
    public function setTenant($tenant)
    {
        $this->tenant = $tenant;

        return $this;
    }

    /**
     * Get the value of advantage
     */ 
    public function getAdvantage()
    {
        return $this->advantage;
    }

    /**
     * Set the value of advantage
     *
     * @return  self
     */ 
    public function setAdvantage($team)
    {
        $this->advantage = $team;

        return $this;
    }

    /**
     * Get the status of advantage
     */ 
    public function isInAdvantage()
    {
        return $this->advantage != 0;
    }
}