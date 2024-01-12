<?php

namespace App\Entity\Scoreboard;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Scoreboard\DeviceRepository")
 * @ORM\Table(name="pdbscrb_device")
 */
class Device
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scoreboard\Tenant", inversedBy="devices")
     */
    private $tenant;

    /**
     * @ORM\Column(type="string", name="device_id")
     */
    private $deviceId;

    /**
     * @ORM\Column(type="datetime", name="expire_date_time")
     */
    private $expireDateTime;

    /**
     * @ORM\Column(type="string", name="token")
     */
    private $token;

    /**
     * @ORM\Column(type="integer", name="field", nullable=true)
     */
    private $field;

    /**
     * @ORM\Column(type="string", name="team1_button_code", nullable=true)
     */
    private $team1ButtonCode;

    /**
     * @ORM\Column(type="string", name="team2_button_code", nullable=true)
     */
    private $team2ButtonCode;

    /**
     * @ORM\Column(type="string", name="team3_button_code", nullable=true)
     */
    private $team3ButtonCode;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

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
     * Get the value of expireDateTime
     */ 
    public function getExpireDateTime()
    {
        return $this->expireDateTime;
    }

    /**
     * Set the value of expireDateTime
     *
     * @return  self
     */ 
    public function setExpireDateTime($expireDateTime)
    {
        $this->expireDateTime = $expireDateTime;

        return $this;
    }

    /**
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

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
     * Get the value of team1ButtonCode
     */ 
    public function getTeam1ButtonCode()
    {
        return $this->team1ButtonCode;
    }

    /**
     * Set the value of team1ButtonCode
     *
     * @return  self
     */ 
    public function setTeam1ButtonCode($team1ButtonCode)
    {
        $this->team1ButtonCode = $team1ButtonCode;

        return $this;
    }

    /**
     * Get the value of team2ButtonCode
     */ 
    public function getTeam2ButtonCode()
    {
        return $this->team2ButtonCode;
    }

    /**
     * Set the value of team2ButtonCode
     *
     * @return  self
     */ 
    public function setTeam2ButtonCode($team2ButtonCode)
    {
        $this->team2ButtonCode = $team2ButtonCode;

        return $this;
    }

    /**
     * Get the value of team3ButtonCode
     */ 
    public function getTeam3ButtonCode()
    {
        return $this->team3ButtonCode;
    }

    /**
     * Set the value of team3ButtonCode
     *
     * @return  self
     */ 
    public function setTeam3ButtonCode($team3ButtonCode)
    {
        $this->team3ButtonCode = $team3ButtonCode;

        return $this;
    }
}