<?php

namespace App\Entity\Scoreboard;


class Selector
{
    const MESSAGE_GET = "MESSAGE_GET";
    const MESSAGE_START = "MESSAGE_START";
    const MESSAGE_SAVE = "MESSAGE_SAVE";
    const MESSAGE_ADD_POINT = "MESSAGE_ADD_POINT";
    const MESSAGE_SUB_POINT = "MESSAGE_SUB_POINT";
    const MESSAGE_STOP = "MESSAGE_STOP";
    const MESSAGE_RESTART = "MESSAGE_RESTART";
    const MESSAGE_ADVERTISING = "MESSAGE_ADVERTISING";

    public string $deviceId;

    public string $message;

    public bool $scoreboard;

    public $match;

    public $advertising;

    public function __construct()
    {
    }

    public function init(
        $deviceId, 
        $message,  
        $match = null,
        $advertising = null)
    {
        $this->deviceId = $deviceId;
        $this->message = $message;
        $this->scoreboard = (
            $message == Selector::MESSAGE_START || 
            $message == Selector::MESSAGE_ADD_POINT || 
            $message == Selector::MESSAGE_SUB_POINT || 
            $message == Selector::MESSAGE_SAVE ||
            $message == Selector::MESSAGE_RESTART
        );
        $this->match = $match;
        $this->advertising = $advertising;
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
     * Get the value of message
     */ 
    public function getMEssage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of scoreboard
     */ 
    public function getScoreboard()
    {
        return $this->scoreboard;
    }

    /**
     * Set the value of scoreboard
     *
     * @return  self
     */ 
    public function setScoreboard($scoreboard)
    {
        $this->scoreboard = $scoreboard;

        return $this;
    }

    /**
     * Get the value of match
     */ 
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * Set the value of match
     *
     * @return  self
     */ 
    public function setMatch($match)
    {
        $this->match = $match;

        return $this;
    }

    /**
     * Get the value of advertising
     */ 
    public function getAdvertising()
    {
        return $this->advertising;
    }

    /**
     * Set the value of advertising
     *
     * @return  self
     */ 
    public function setAdvertising($advertising)
    {
        $this->advertising = $advertising;

        return $this;
    }
}