<?php

namespace TestApp\ImagesProcess\Domain;

class Photo
{
    const PHOTOSPATH = __DIR__ . "/../../../assets/files";
    private $photo;

    public function __construct($photo)
    {
        $this->photo = $photo; 
    }

    public function photo()
    {
        return $this->photo;
    }
}