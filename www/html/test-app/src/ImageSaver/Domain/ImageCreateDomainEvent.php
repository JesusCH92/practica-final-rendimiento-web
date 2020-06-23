<?php

namespace TestApp\ImageSaver\Domain;

use Symfony\Contracts\EventDispatcher\Event;

class ImageCreateDomainEvent extends Event
{
    const EVENTNAME = 'save.image';
    private string $imagePath;
    private string $imageName;
    private string $imageExtension;
    private string $filterAdded;

    public function __construct($imagePath, $imageName, $imageExtension, $filterAdded)
    {   
        $this->imagePath = $imagePath;
        $this->imageName = $imageName;
        $this->imageExtension = $imageExtension;
        $this->filterAdded = $filterAdded;
    }

    public function imagePath()
    {
        return $this->imagePath;
    }

    public function imageName()
    {
        return $this->imageName;
    }

    public function imageExtension()
    {
        return $this->imageExtension;
    }

    public function filterAdded()
    {
        return $this->filterAdded;
    }
}