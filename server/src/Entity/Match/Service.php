<?php

namespace App\Entity\Match;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Match\ServiceRepository")
 * @ORM\Table(name="wp2e_bkntc_services")
 */
class Service
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="name")
     */
    private $name;

    /**
     * @ORM\Column(type="integer", name="min_capacity")
     */
    private $minCapacity;

    /**
     * @ORM\Column(type="integer", name="max_capacity")
     */
    private $maxCapacity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Match\Tenant", inversedBy="appointments")
     */
    private $tenant;

    

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
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of minCapacity
     */ 
    public function getMinCapacity()
    {
        return $this->minCapacity;
    }

    /**
     * Set the value of minCapacity
     *
     * @return  self
     */ 
    public function setMinCapacity($minCapacity)
    {
        $this->minCapacity = $minCapacity;

        return $this;
    }

    /**
     * Get the value of maxCapacity
     */ 
    public function getMaxCapacity()
    {
        return $this->maxCapacity;
    }

    /**
     * Set the value of maxCapacity
     *
     * @return  self
     */ 
    public function setMaxCapacity($maxCapacity)
    {
        $this->maxCapacity = $maxCapacity;

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
}