<?php

namespace App\Entity\Scoreboard;

class Advertising
{
    const IMAGE_FOOTER = "footer";
    const IMAGE_SLIDE_1 = "slide_1";
    const IMAGE_SLIDE_2 = "slide_2";
    const IMAGE_SLIDE_3 = "slide_3";
    const IMAGE_SLIDE_4 = "slide_4";

    public array $images;

    public function __construct(array $images = [])
    {
        $this->images = $images;
    }

    /**
     * Get the value of images
     */ 
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set the value of images
     *
     * @return  self
     */ 
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }
}